<?php

namespace App\Http\Controllers;

use App\Services\MenuServiceClient;
use App\Services\OrderServiceClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected OrderServiceClient $orderService;
    protected MenuServiceClient $menuService;

    public function __construct(OrderServiceClient $orderService, MenuServiceClient $menuService)
    {
        $this->orderService = $orderService;
        $this->menuService = $menuService;
    }

    public function index(): Response
    {
        $orders = $this->orderService->all() ?? ['data' => []];
        $allOrders = collect($orders['data'] ?? []);

        $totalMenuItems = $allOrders->sum(function ($order) {
            return collect($order['items'] ?? [])->sum('quantity');
        });

        $totalRevenue = $allOrders->sum(function ($order) {
            return collect($order['items'] ?? [])->sum(function ($item) {
                return ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 0);
            });
        });

        $staffNumber = DB::table('users')->count();

        return Inertia::render('Reports/Index', [
            'ordersNumber' => $allOrders->count(),
            'totalRevenue' => round($totalRevenue, 2),
            'totalMenuItems' => $totalMenuItems,
            'staffNumber' => $staffNumber,
            'allOrders' => Inertia::optional(fn () => $orders),
            'mostSoldItems' => Inertia::optional(fn () => $this->mostSoldItems()),
        ]);
    }

    public function export(): StreamedResponse
    {
        $dateStamp = date('Y-m-d_H-i-s');
        $csvFileName = "orders-$dateStamp.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $csvFileName),
        ];

        $orders = ($this->orderService->all() ?? ['data' => []])['data'];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Ordered At', 'Status', 'Desk/Group', 'Menu Item IDs']);

            foreach ($orders as $order) {
                $menuItemIds = collect($order['items'] ?? [])->pluck('menu_item_id')->implode(', ');
                $deskOrGroup = ! empty($order['desk_id']) ? "Desk: {$order['desk_id']}" : "Group: {$order['group_id']}";
                fputcsv($handle, [
                    $order['uuid'] ?? $order['id'],
                    $order['ordered_at'] ?? $order['orderedAt'] ?? '',
                    $order['status'],
                    $deskOrGroup,
                    $menuItemIds,
                ]);
            }

            fclose($handle);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }

    public function getAllOrders(): JsonResponse
    {
        $orders = $this->orderService->all() ?? ['data' => []];

        return response()->json($orders);
    }

    public function getOrdersByDate(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = collect(($this->orderService->all() ?? ['data' => []])['data'])
            ->filter(function ($order) use ($startDate, $endDate) {
                return $order['ordered_at'] >= $startDate && $order['ordered_at'] <= $endDate;
            })
            ->values();

        return response()->json(['data' => $orders]);
    }

    public function getMostSoldItems(): JsonResponse
    {
        $menuItems = $this->mostSoldItems();

        return response()->json($menuItems);

    }

    private function mostSoldItems(): array
    {
        $items = $this->orderService->mostSoldItems() ?? [];
        $menuItemIds = collect($items)->pluck('menu_item_id')->map('intval')->all();
        $menuItems = collect($this->menuService->getItems($menuItemIds, session('locale', 'en')))->keyBy('id');

        return collect($items)->map(function ($item) use ($menuItems) {
            $menuItem = $menuItems->get((int) $item['menu_item_id']);

            return [
                'name' => $menuItem['name'] ?? (string) $item['menu_item_id'],
                'quantity' => (int) $item['quantity'],
            ];
        })->values()->all();
    }
}
