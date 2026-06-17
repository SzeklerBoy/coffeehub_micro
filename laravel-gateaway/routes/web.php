<?php

use App\Http\Controllers\Api\MenuItemController as ApiMenuItemController;
use App\Http\Controllers\Api\RoomController as RoomApiController;
use App\Http\Controllers\Auth\DashBoardController;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TranslationController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashBoardController::class, 'index'])->name('home');

Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::get('/orders/{order:uuid}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::delete('/orders/{order:uuid}/menu/{menuItem}/delete', [OrderController::class, 'removeItem'])->name('orders.menuitem.destroy');
Route::get('/orders/{order:uuid}/checkout', [OrderController::class, 'payAll'])->name('orders.checkout.all');
Route::get('/orders/{order:uuid}/checkout/callback', [OrderController::class, 'checkoutCallback'])->name('orders.checkout.callback');
Route::get('/menu-cat', [MenuItemController::class, 'getByCategory'])->name('menu.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/desks', [DeskController::class, 'index'])->name('desks.index');
    Route::get('/desks/{desk}/code/create', [DeskController::class, 'generateCode'])->name('desks.code.create');
    Route::get('/desks/{desk}/code/delete', [DeskController::class, 'clearCode'])->name('desks.code.delete');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::get('/groups/{group}/code/create', [GroupController::class, 'generateCode'])->name('groups.code.create');
    Route::get('/groups/{group}/code/delete', [GroupController::class, 'clearCode'])->name('groups.code.delete');
    Route::get('/menu', [MenuItemController::class, 'index'])->name('menu.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('/orders/{order:uuid}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{order:uuid}/edit', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order:uuid}/checkout', [OrderController::class, 'createPayment'])->name('orders.checkout');
    Route::get('/desks/{desk}/orders/create', [OrderController::class, 'createFromDesk'])->name('desks.orders.create');
    Route::get('/desks/{desk}/orders', [DeskController::class, 'orderIndex'])->name('desks.orders.index');
    Route::post('/desks/{desk}/orders', [OrderController::class, 'storeDeskOrder'])->name('desks.orders.store');
    Route::get('/groups/{group}/orders/create', [OrderController::class, 'createFromGroup'])->name('groups.orders.create');
    Route::get('/groups/{group}/orders', [GroupController::class, 'orderIndex'])->name('groups.orders.index');
    Route::post('/groups/{group}/orders', [OrderController::class, 'storeGroupOrder'])->name('groups.orders.store');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/staff', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/desks/create', [DeskController::class, 'create'])->name('desks.create');
    Route::post('/desks', [DeskController::class, 'store']);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/staff/{user}/edit', [ProfileController::class, 'editAdmin'])->name('profile.edit-admin');
    Route::patch('/staff/{user}', [ProfileController::class, 'updateAdmin'])->name('profile.update-admin');
    Route::get('/menu/export', [MenuItemController::class, 'export'])->name('menu.export');
    Route::post('/menu/import', [MenuItemController::class, 'import'])->name('menu.import');
    Route::get('/menu/{item}/edit', [MenuItemController::class, 'edit'])->name('menu.edit');
    Route::patch('/menu/{item}', [MenuItemController::class, 'update'])->name('menu.update');
    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations/export', [TranslationController::class, 'exportXLIFF'])->name('translations.export');
    Route::post('/translations/import', [TranslationController::class, 'import'])->name('translations.import');
    Route::get('/api/users', [ProfileController::class, 'fetchUsers'])->name('api.users');
});

Route::resource('menu', MenuItemController::class)->except(['index', 'show'])->middleware(['auth', AdminMiddleware::class]);

Route::get('/api/menu', [ApiMenuItemController::class, 'index'])->name('api.menuitems.index');
Route::post('/api/menu', [ApiMenuItemController::class, 'store'])->name('api.menuitems.store');
Route::get('/api/menu/{menuitem}', [ApiMenuItemController::class, 'show'])->name('api.menuitems.show');
Route::put('/api/menu/{menuitem}', [ApiMenuItemController::class, 'update'])->name('api.menuitems.update');
Route::delete('/api/menu/{menuitem}', [ApiMenuItemController::class, 'destroy'])->name('api.menuitems.destroy');
Route::post('/api/menu/{menuitem}/image', [ApiMenuItemController::class, 'storeImage'])->name('menuitems.image.store');
Route::patch('/api/menu/{menuitem}/image', [ApiMenuItemController::class, 'updateImage'])->name('menuitems.image.update');
// put doesn't work for some reason, the image doesn't upload and the path does not change

Route::get('/api/desks', [DeskController::class, 'apiIndex'])->name('api.desks.index');
Route::post('/api/desks', [DeskController::class, 'apiStore'])->name('api.desks.store');
Route::delete('/api/desks/{desk}', [DeskController::class, 'destroy'])->name('api.desks.destroy');
Route::delete('/api/desks', [DeskController::class, 'destroyAll'])->name('api.desks.destroyAll');
Route::get('/api/desks/newConfig', [DeskController::class, 'newConfig'])->name('api.desks.newConfig');

Route::get('/api/rooms', [RoomApiController::class, 'index'])->name('api.rooms.index');
Route::post('/api/rooms', [RoomApiController::class, 'store'])->name('api.rooms.store');
Route::put('/api/rooms/{room}', [RoomApiController::class, 'update'])->name('api.rooms.update');

Route::get('/api/orders/{order:uuid}/menu', [OrderController::class, 'getMenuItems'])->name('api.orders.menuItems');

Route::post('/api/setLocale', function (Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    session(['locale' => $locale]);

    return response()->json(['Local changed', 200]);
})->name('api.setLocale');

require __DIR__.'/auth.php';

// TESTING ROUTES
// TODO: Remove these routes:

// This is a test route to get the CSRF token for Postman testing, should be removed in production
// TODO: Remove this route in production
Route::get('/api/test', function () {
    return response()->json(csrf_token());
});

Route::inertia('/test', 'Test')->name('test');

Route::get('test/menu/create', function () {
    $distinctCategories = MenuItem::distinct()->pluck('category');

    return Inertia::render('Menu/Create', ['categories' => $distinctCategories]);
});
// Route::inertia('/test/menu/create', 'Menu/Create')->name('test.menu.create');
