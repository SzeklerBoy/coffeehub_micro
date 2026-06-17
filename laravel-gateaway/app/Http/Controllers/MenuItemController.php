<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    private string $menuServiceUrl;

    public function __construct()
    {
        $this->menuServiceUrl = config('services.microservices.menu');
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Menu/Index', [
            'userRole' => auth()->user()->getAttribute('role'),
        ]);
    }

    public function create(Request $request): \Inertia\Response
    {
        $locale = $request->input('locale', session('locale', 'en'));

        $response = Http::timeout(3)->get("{$this->menuServiceUrl}/categories", [
            'locale' => $locale
        ]);

        $distinctCategories = $response->successful() ? $response->json() : [];

        return Inertia::render('Menu/Create', [
            'categories' => $distinctCategories,
        ]);
    }

    public function store(StoreMenuItemRequest $request): RedirectResponse
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->storePublicly('menu_item_avatars', 'public');
            $imagePath = "/storage/{$path}";
        }

        $payload = [
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'image_path' => $imagePath,
            'ETAinMinutes' => $request->input('ETA'),
            'locale' => $request->input('locale', 'en'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
        ];

        $response = Http::timeout(5)->post("{$this->menuServiceUrl}/menu", $payload);

        if ($response->failed()) {
            if ($imagePath) Storage::disk('public')->delete(str_replace('/storage/', '', $imagePath));
            return redirect()->route('menu.index')->with('error', 'Menu service is currently unavailable.');
        }

        return redirect()->route('menu.index')->with('success', 'Menu item created successfully.');
    }

    public function edit(int $id): \Inertia\Response
    {
        Log::info('Editing menu item', ['item_id' => $id]);

        $locale = session('locale', 'en');

        $itemResponse = Http::timeout(5)->get("{$this->menuServiceUrl}/menu", ['locale' => $locale]);
        
        if ($itemResponse->failed()) {
            abort(404, 'Menu item not found in Menu Service');
        }

        $items = collect($itemResponse->json());
        $item = $items->firstWhere('id', $id);

        if (!$item) abort(404, 'Menu item not found');

        $catResponse = Http::timeout(3)->get("{$this->menuServiceUrl}/categories", ['locale' => $locale]);
        $distinctCategories = $catResponse->successful() ? $catResponse->json() : [];

        return Inertia::render('Menu/Edit', [
            'item' => $item, 
            'categories' => $distinctCategories,
        ]);
    }

    public function update(UpdateMenuItemRequest $request, int $id): RedirectResponse
    {
        $payload = $request->only(['ETAinMinutes', 'quantity', 'price', 'name', 'description', 'category']);
        $payload['locale'] = session('locale', 'en');

        if ($request->hasFile('image')) {
            $payload['image_path'] = '/storage/'.$request->file('image')->storePublicly('menu_item_avatars', 'public');
        }

        $response = Http::timeout(5)->put("{$this->menuServiceUrl}/menu/{$id}", $payload);

        if ($response->failed()) {
            return back()->with('error', 'Failed to update menu item in Menu Service.');
        }

        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function getByCategory(Request $request)
    {
        $category = $request->input('category');
        $page = $request->input('page', 1);
        $locale = session('locale', 'en');

        $response = Http::timeout(5)->get("{$this->menuServiceUrl}/menu", [
            'category' => $category,
            'locale' => $locale,
        ]);

        if ($response->failed()) {
            return response()->json(['data' => [], 'prev_page_url' => null, 'next_page_url' => null]);
        }

        $items = $response->json();
        $perPage = 6;
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        return response()->json([
            'data' => $paginatedItems,
            'prev_page_url' => $page > 1 ? "/menu-cat?category={$category}&page=" . ($page - 1) : null,
            'next_page_url' => $page < $totalPages ? "/menu-cat?category={$category}&page=" . ($page + 1) : null,
        ]);
    }


    public function destroy(int $id): RedirectResponse
    {
        $response = Http::timeout(5)->delete("{$this->menuServiceUrl}/menu/{$id}");

        if ($response->failed()) {
            $errorMessage = $response->json('detail') ?? 'Failed to delete menu item.';
            return redirect()->route('menu.index')->with('error', $errorMessage);
        }

        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully.');
    }

    public function export()
    {
        $dateStamp = date('Y-m-d_H-i-s');
        $csvFileName = "menu-$dateStamp.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $csvFileName),
        ];

        $response = Http::timeout(10)->get("{$this->menuServiceUrl}/menu", ['locale' => 'en']);
        $menuItems = $response->successful() ? $response->json() : [];

        $callback = function () use ($menuItems) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Category', 'ETA', 'Description', 'Quantity', 'Price']);

            foreach ($menuItems as $item) {
                fputcsv($handle, [
                    $item['name'], 
                    $item['category'], 
                    $item['ETAinMinutes'], 
                    $item['description'], 
                    $item['quantity'], 
                    $item['price']
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $action = $request->input('action');
        $file = $request->file('file');
        $csv = array_map('str_getcsv', file($file));

        $locale = session('locale', 'en');

        foreach ($csv as $index => $row) {
            if ($index === 0 || count($row) < 6) continue;

            $payload = [
                'name' => $row[0],
                'category' => $row[1],
                'ETAinMinutes' => (float) $row[2],
                'description' => $row[3],
                'quantity' => (float) $row[4],
                'price' => (float) $row[5],
                'locale' => $locale
            ];

            if ($action === 'new') {
                Http::timeout(5)->post("{$this->menuServiceUrl}/menu", $payload);
            } elseif ($action === 'update') {
                 Http::timeout(5)->post("{$this->menuServiceUrl}/menu", $payload);
            }
        }

        return redirect()->route('menu.index')->with('success', 'Menu items imported successfully.');
    }
}