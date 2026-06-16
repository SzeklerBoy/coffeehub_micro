<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMenuItemRequest;
use App\Http\Requests\Api\UpdateMenuItemRequest;
use App\Models\Language;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    private string $menuServiceUrl;

    public function __construct()
    {
        $this->menuServiceUrl = config('services.microservices.menu');
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        $search = $request->input('search');

        $locale = session('locale', 'en');

        $response = Http::timeout(3)->get($this->menuServiceUrl . '/menu', [
        'locale' => $locale,
        'search' => $search
        ]);

        if ($response->failed()) {
        // Ha a FastAPI épp újraindul vagy hiba van, ne a Laravel omoljon össze 500-as hibával!
        return response()->json(['error' => 'Menu service is currently unavailable.'], 503);
        }

        Log::info('Menu items fetched successfully from Menu Service.', ['response' => $response->json()]);
        // 4. Az adatok visszaadása a Vue/Inertia felületnek
        return response()->json($response->json());
    
    }

    public function store(StoreMenuItemRequest $request): JsonResponse
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

        $response = Http::timeout(5)->post($this->menuServiceUrl . '/menu', $payload);

        if ($response->failed()) {
            if ($imagePath) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $imagePath));
            }
        
        return response()->json(['error' => 'Failed to create menu item in Menu Service.'], 503);
        }
       
        return response()->json($response->json(),201);
    }

    public function show(MenuItem $menuitem): JsonResponse
    {
        return response()->json($menuitem);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuitem): JsonResponse
    {
        $menuitem->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'ETAinMinutes' => $request->input('ETA'),
        ]);

        return response()->json($menuitem);
    }

    public function destroy(MenuItem $menuItem): JsonResponse
    {
        if ($menuItem->quantity > 0) {
            return response()->json(['message' => 'Cannot delete menu item with quantity greater than 0.'], 400);
        }
        $menuItem->delete();

        return response()->json(['message' => 'Menu item deleted successfully.'], 204);
    }

    /**
     * Store the image of a menu item.
     *
     * Only the first image will be stored. If you want to update the image, use the updateImage() method.
     */
    public function storeImage(Request $request, MenuItem $menuitem): JsonResponse
    {
        if ($menuitem->image_path) {
            return response()->json(['message' => 'Menu item already has an image.'], 400);
        }
        $menuitem->update([
            'image_path' => $request->has('image') ? "/storage/{$request->file('image')->storePublicly('menu_item_avatars', 'public')}" : null,
        ]);

        return response()->json($menuitem);
    }

    /**
     * Update the image of a menu item.
     *
     * If you find that the image is not provided, you might need to use post method
     * with a "_method" field set to "PATCH" in the form.
     */
    public function updateImage(Request $request, MenuItem $menuitem): JsonResponse
    {

        if ($request->has('image')) {
            $menuitem->update([
                'image_path' => "/storage/{$request->file('image')->storePublicly('menu_item_avatars', 'public')}",
            ]);
        } else {
            return response()->json(['message' => 'No image provided.'], 400);
        }

        return response()->json($menuitem);
    }
}
