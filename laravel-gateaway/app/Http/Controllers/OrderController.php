<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Desk;
use App\Models\Group;
use App\Models\MenuItem;
use App\Services\MenuServiceClient;
use App\Services\OrderServiceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;

class OrderController extends Controller
{
    private StripeClient $stripe;
    protected OrderServiceClient $orderService;
    protected MenuServiceClient $menuService;

    private string $orderServiceUrl;
    private string $menuServiceUrl;
    private string $deskServiceUrl;

    public function __construct(OrderServiceClient $orderService, MenuServiceClient $menuService)
    {
        $this->orderService = $orderService;
        $this->menuService = $menuService;
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
        Stripe::setApiKey(env('STRIPE_KEY'));
        
        $this->orderServiceUrl = config('services.microservices.order');
        $this->menuServiceUrl  = config('services.microservices.menu');
        $this->deskServiceUrl  = config('services.microservices.desk');
    }

    public function index(Request $request): Response
    {
        $orders = $this->orderService->list([
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_field' => $request->get('sort_field', 'id'),
            'sort_direction' => $request->get('sort_direction', 'ASC'),
            'limit' => 9,
            'page' => max((int) $request->get('page', 1), 1),
        ]) ?? ['data' => []];

        Log::info('Orders retrieved: ', $orders);

        return Inertia::render('Orders/Index', [
            'orders' => $this->normalizeOrderPagination($orders),
        ]);
    }

    public function show(string $order): Response
    {
        $orderData = $this->orderService->find($order);
        abort_if(! $orderData, 404);

        $orderData = $this->normalizeOrder($orderData);
        $waiterCount = DB::table('users')->where('role', 'waitress')->count() ?: 1;
        $eta = $this->orderService->eta($order, $waiterCount);

        $component = Auth::check() ? 'Orders/ShowStaff' : 'Orders/ShowGuest';

        return Inertia::render($component, [
            'order' => $orderData,
            'willBeCompletedAt' => $eta['willBeCompletedAt'] ?? now()->toIso8601String(),
        ]);
    }

    public function update(UpdateOrderRequest $request, string $order): RedirectResponse
    {
        $updatedOrder = $this->orderService->updateStatus($order, $request->input('status'));

        if (! $updatedOrder) {
            return back()->with('error', 'Something went wrong with the order! Please try again.');
        }

        if (in_array($updatedOrder['status'] ?? null, ['completed', 'cancelled'], true)) {
            if (! empty($updatedOrder['desk_id'])) {
                Http::timeout(3)->put($this->deskServiceUrl . "/desks/{$updatedOrder['desk_id']}/release");
            }

            if (! empty($updatedOrder['group_id'])) {
                Http::timeout(3)->put($this->deskServiceUrl . "/groups/{$updatedOrder['group_id']}/release");
            }
        }

        return back()->with('success', 'Order updated successfully.');
    }

    public function removeItem(string $order, MenuItem $menuItem): RedirectResponse
    {
        $response = $this->orderService->removeItem($order, $menuItem->id);

        if (! $response) {
            return back()->with('error', 'Something went wrong with the order! Please try again.');
        }

        if (($response['error'] ?? null) === 'Order preparation already started.') {
            return back()->with('error', 'Order preparation already started.');
        }

        if (($response['deleted'] ?? false) === true) {
            if (Auth::check()) {
                return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
            }

            return redirect()->route('orders.create')->with('success', 'Order deleted successfully.');
        }

        return back()->with('success', 'Item removed successfully.');
    }

    public function destroy(string $order): RedirectResponse
    {
        $orderData = $this->orderService->find($order);

        if (! $orderData) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        if (! $this->orderService->delete($order)) {
            return redirect()->route('orders.index')->with('error', 'Something went wrong with the order! Please try again.');
        }

        if (! empty($orderData['desk_id'])) {
            Http::timeout(3)->put($this->deskServiceUrl . "/desks/{$orderData['desk_id']}/release");
        } elseif (! empty($orderData['group_id'])) {
            Http::timeout(3)->put($this->deskServiceUrl . "/groups/{$orderData['group_id']}/release");
        }

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function create(Request $request): Response
    {
        $distinctCategories = $this->menuService->getCategories(session('locale', 'en'));
        $desk = $request->input('desk') ? (int) $request->input('desk') : null;
        $group = $request->input('group') ? (int) $request->input('group') : null;

        return Inertia::render('Orders/Create', [
            'group' => $group,
            'desk' => $desk,
            'categories' => $distinctCategories,
        ]);
    }

    public function createFromDesk(Desk $desk): View
    {
        return $this->createOrderViewForModel($desk);
    }

    public function createFromGroup(Group $group): View
    {
        return $this->createOrderViewForModel($group);
    }

    public function storeDeskOrder(Desk $desk, Request $request): RedirectResponse
    {
        $order = $this->createOrderForTarget($desk, json_decode($request->cart, true) ?? []);

        return $this->handleCreatedOrder($order);
    }

    public function storeGroupOrder(Group $group, Request $request): RedirectResponse
    {
        $order = $this->createOrderForTarget($group, json_decode($request->cart, true) ?? []);

        return $this->handleCreatedOrder($order);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $order = $this->createOrderForTarget(null, json_decode($request->cart, true) ?? [], $request->input('code'));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return $this->handleCreatedOrder($order, true);
    }

    public function createPayment(string $order, CreatePaymentRequest $request): Response|RedirectResponse
    {
        $items = $request->input('items');
        if ($items === null || $items === []) {
            return back()->with('error', 'Nothing to pay.');
        }

        return $this->createCheckoutSession($order, $items);
    }

    public function payAll(string $order): Response|RedirectResponse
    {
        $orderData = $this->orderService->find($order);

        if (! $orderData || empty($orderData['items'])) {
            return redirect()->route('orders.show', $order)->with('error', 'Nothing to pay.');
        }

        $items = [];
        foreach ($orderData['items'] as $item) {
            $remainingQuantity = (int) ($item['quantity'] ?? 0) - (int) ($item['paid'] ?? 0);

            if ($remainingQuantity > 0) {
                $items[] = [
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $remainingQuantity,
                ];
            }
        }

        if ($items === []) {
            return redirect()->route('orders.show', $order)->with('error', 'Nothing to pay.');
        }

        return $this->createCheckoutSession($order, $items);
    }

    public function checkoutCallback(string $order, Request $request): RedirectResponse
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($request->query('session'), ['expand' => ['line_items']]);
            if ($session->payment_status !== 'unpaid') {
                foreach ($session->line_items->data as $line_item) {
                    $menuItemId = $this->stripe->products->retrieve($line_item->price->product, [])->metadata['menu_item_id'];
                    $this->orderService->markItemPaid($order, (int) $menuItemId, (int) $line_item->quantity);
                }

                return redirect()->route('orders.show', $order)->with('success', 'Payment completed');
            }
        } catch (ApiErrorException|InvalidArgumentException) {
            return redirect()->route('orders.show', $order)->with('error', 'Something went wrong with the payment. Please try again later.');
        }

        return redirect()->route('orders.show', $order)->with('error', 'Payment failed');
    }

    public function getMenuItems(Request $request, string $order): JsonResponse
    {
        $orderData = $this->orderService->find($order);
        abort_if(! $orderData, 404);

        return response()->json($this->enrichMenuItems($orderData['items'] ?? []));
    }

    private function createOrderViewForModel(Desk|Group $model): View
    {
        $locale = session('locale', 'en');
        $distinctCategories = $this->menuService->getCategories($locale);
        $items = $this->menuService->getItems(locale: $locale);

        return match (true) {
            $model instanceof Desk => view('orders.create-staff', [
                'items' => $items,
                'desk' => $model,
                'group' => '',
                'categories' => $distinctCategories,
            ]),
            $model instanceof Group => view('orders.create-staff', [
                'items' => $items,
                'group' => $model,
                'desk' => '',
                'categories' => $distinctCategories,
            ]),
            default => view('orders.create-guest', [
                'items' => $items,
                'group' => '',
                'desk' => '',
                'categories' => $distinctCategories,
            ]),
        };
    }

    private function createOrderForTarget(Desk|Group|null $target, array $cartItems, ?string $code = null): ?array
    {
        if ($code) {
            $desk = Desk::query()->where('code', $code)->first();
            $group = Group::query()->where('code', $code)->first();
            $target = $desk ?? $group;
            $description = "Created by guest with code: $code";
        } elseif (Auth::check()) {
            $description = sprintf('Created by %s', Auth::user()->name);
        } else {
            throw new \RuntimeException('Code not provided and user not authenticated.');
        }

        if (! $target) {
            Log::error('No valid target found for order creation.', ['code' => $code, 'cartItems' => $cartItems]);
            return null;
        }

        $itemIds = array_keys($cartItems);
        $menuItems = collect($this->menuService->getItems(array_map('intval', $itemIds)))->keyBy('id');

        $items = [];
        $totalPrepTime = 0;

        foreach ($cartItems as $itemId => $item) {
            $menuItem = $menuItems->get((int) $itemId);
            if (! $menuItem) {
                continue;
            }

            if ($menuItem['quantity'] < 1) {
                Log::warning('Menu item out of stock.', ['menu_item_id' => $menuItem['id']]);
                return null;
            }

            $quantity = min((int) $menuItem['quantity'], (int) $item['quantity']);
            if ($quantity < 1) {
                continue;
            }

            $totalPrepTime += ($menuItem['ETAinMinutes'] ?? 0) * $quantity;
            $items[] = [
                'menu_item_id' => $menuItem['id'],
                'quantity' => $quantity,
                'unit_price' => $menuItem['price'],
                'prep_time' => $menuItem['ETAinMinutes'] ?? 0,
            ];
        }

        if ($items === []) {
            Log::warning('No valid items found for order creation.', ['cartItems' => $cartItems]);
            return null;
        }

        Log::info('Creating order for target.', [
            'target_type' => $target instanceof Desk ? 'Desk' : 'Group',
            'target_id' => $target->id,
            'description' => $description,
            'totalPrepTime' => $totalPrepTime,
            'items' => $items,
        ]);

        $createdOrder = $this->orderService->create([
            'desk_id' => $target instanceof Desk ? $target->id : null,
            'group_id' => $target instanceof Group ? $target->id : null,
            'waiter_id' => Auth::user()?->id,
            'description' => $description,
            'totalPrepTime' => $totalPrepTime,
            'status' => 'ordered',
            'items' => $items,
        ]);

        Log::info('Order creation response from OrderServiceClient.', ['response' => $createdOrder]);

        if (! $createdOrder) {
            return null;
        }

        if ($target instanceof Desk) {
            $target->update(['is_occupied' => true]);
        }

        if ($target instanceof Group) {
            $target->desks->each->update(['is_occupied' => true]);
        }

        return $createdOrder;
    }

    private function handleCreatedOrder(?array $order, bool $returnBack = false): RedirectResponse
    {
        if (! $order) {
            return back()->with('error', 'Something went wrong with the order! Please try again.');
        }

        if ($returnBack) {
            return back()->with([
                'success' => 'Order placed!',
                'next' => route('orders.show', ['order' => $order['uuid']]),
            ]);
        }

        return redirect()->route('orders.show', ['order' => $order['uuid']]);
    }

    private function createCheckoutSession(string $orderUuid, array $items): Response|RedirectResponse
    {
        $locale = session('locale', 'en');
        $menuItemIds = collect($items)->filter(fn ($i) => $i['quantity'] ?? 0)->pluck('menu_item_id')->map('intval')->all();
        $menuItems = collect($this->menuService->getItems($menuItemIds, $locale))->keyBy('id');

        $lineItems = [];

        foreach ($items as $item) {
            if (! ($item['quantity'] ?? 0)) {
                continue;
            }

            $menuItem = $menuItems->get((int) $item['menu_item_id']);
            if (! $menuItem) {
                continue;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'ron',
                    'product_data' => [
                        'name' => $menuItem['name'],
                        'description' => $menuItem['description'] ?? '',
                        'metadata' => [
                            'menu_item_id' => $item['menu_item_id'],
                        ],
                    ],
                    'unit_amount' => (int) round($menuItem['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($lineItems === []) {
            return back()->with('error', 'Nothing to pay.');
        }

        try {
            $checkoutSession = $this->stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'ui_mode' => 'embedded',
                'return_url' => route('orders.checkout.callback', $orderUuid).'?session={CHECKOUT_SESSION_ID}',
            ]);
        } catch (ApiErrorException) {
            return back()->with('error', 'Something went wrong with the payment. Please try again later.');
        }

        return Inertia::render('Checkout/Index', [
            'client_secret' => $checkoutSession->client_secret,
            'api_key' => Stripe::getApiKey(),
        ]);
    }

    private function normalizeOrderPagination(array $payload): array
    {
        $payload['data'] = array_map(fn ($order) => $this->normalizeOrder($order), $payload['data'] ?? []);

        return $payload;
    }

    private function normalizeOrder(array $order): array
    {
        if (isset($order['items']) && ! isset($order['menu_items'])) {
            $order['menu_items'] = $this->enrichMenuItems($order['items']);
        }

        if (isset($order['createdAt']) && ! isset($order['created_at'])) {
            $order['created_at'] = $order['createdAt'];
        }

        if (isset($order['updatedAt']) && ! isset($order['updated_at'])) {
            $order['updated_at'] = $order['updatedAt'];
        }

        if (isset($order['orderedAt']) && ! isset($order['ordered_at'])) {
            $order['ordered_at'] = $order['orderedAt'];
        }

        if (isset($order['completedAt']) && ! isset($order['completed_at'])) {
            $order['completed_at'] = $order['completedAt'];
        }

        unset($order['items'], $order['createdAt'], $order['updatedAt'], $order['orderedAt'], $order['completedAt']);

        return $order;
    }

    private function enrichMenuItems(array $items): array
    {
        $locale = session('locale', 'en');
        $menuItemIds = collect($items)->pluck('menu_item_id')->filter()->map('intval')->all();
        $menuItems = collect($this->menuService->getItems($menuItemIds, $locale))->keyBy('id');

        return collect($items)->map(function ($item) use ($menuItems) {
            $menuItem = $menuItems->get((int) $item['menu_item_id']);

            if (! $menuItem) {
                return null;
            }

            return array_merge($menuItem, [
                'pivot' => [
                    'quantity' => $item['quantity'] ?? 0,
                    'paid' => $item['paid'] ?? 0,
                ],
            ]);
        })->filter()->values()->all();
    }
}
