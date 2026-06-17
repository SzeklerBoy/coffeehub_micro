<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\DeskApiRequest;
use App\Http\Requests\DeskRequest;
use App\Models\Desk;
use App\Models\Room;
use App\Services\OrderServiceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class DeskController extends Controller
{
    protected string $deskServiceUrl;
    protected OrderServiceClient $orderService;

    public function __construct(OrderServiceClient $orderService)
    {
        $this->orderService = $orderService;
        $this->deskServiceUrl = config('services.microservices.desk');
    }

    // =========================================================================
    // Inertia / Web Methods
    // =========================================================================

    public function index(): Response
    {
        $response = Http::timeout(3)->get($this->deskServiceUrl . '/desk-management-data');

        $desksProp = $response->successful()
            ? $response->json('desksProp', ['groups' => [], 'standaloneDesks' => []])
            : ['groups' => [], 'standaloneDesks' => []];

        return Inertia::render('Desks/Index', [
            'desksProp' => $desksProp,
            'rooms'     => Room::all(),
        ]);
    }

    public function orderIndex(int $desk): Response
    {
        $orders = $this->orderService->list(['desk_id' => $desk, 'limit' => 9]) ?? ['data' => []];

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Desks/Create', [
            'rooms' => Room::all(),
        ]);
    }

    /**
     * Store a newly created desk via the Go microservice (form submission).
     */
    public function store(DeskRequest $request): RedirectResponse
    {
        Http::timeout(3)->post($this->deskServiceUrl . '/desks', [
            'number_of_seats' => $request->input('number_of_seats'),
            'description' => $request->input('description'),
        ]);

        return redirect('/desks')->with('success', 'Desk created successfully');
    }

    public function generateCode(Desk $desk): JsonResponse
    {
        $code = fake()->randomNumber(5, true);

        $desk->update([
            'is_occupied' => true,
            'code' => $code,
        ]);

        return response()->json(['code' => $code]);
    }

    public function clearCode(Desk $desk): JsonResponse
    {
        $desk->update([
            'is_occupied' => false,
            'code' => null,
        ]);

        return response()->json([
            'message' => "Code for Desk#$desk->id cleared successfully",
        ]);
    }

    public static function freeInactiveDesks(): void
    {
        $desks = Desk::where('is_occupied', true)->has('orders')->get();

        foreach ($desks as $desk) {
            if ($desk->activeOrders()->count() === 0 && $desk->latestOrder()->first()->updated_at->diffInUTCMinutes() > 30) {
                $desk->update([
                    'is_occupied' => false,
                    'code' => null,
                ]);
            }
        }
    }

    // =========================================================================
    // API / JSON Methods — proxied to the Go desk microservice
    // =========================================================================

    /**
     * Return groups + standalone desks as JSON.
     * Used by the staff canvas view (ShowStaff component).
     */
    public function apiIndex(): JsonResponse
    {
        $response = Http::timeout(3)->get($this->deskServiceUrl . '/desk-management-data');

        if ($response->failed()) {
            return response()->json(['desksProp' => ['groups' => [], 'standaloneDesks' => []], 'rooms' => []], 502);
        }

        $data = $response->json();

        return response()->json([
            'desksProp' => $data['desksProp'],
            'rooms' => [],
        ]);
    }

    /**
     * Bulk upsert desk canvas positions (x, y, nrOfSeats).
     * Used by the admin canvas editor (Create.vue) on save.
     */
    public function apiStore(DeskApiRequest $request): JsonResponse
    {
        $response = Http::timeout(3)->post($this->deskServiceUrl . '/desks/canvas', [
            'desks' => $request->input('desks'),
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to save desks'], 502);
        }

        return response()->json($response->json());
    }

    /**
     * Delete a single desk via the Go microservice.
     */
    public function destroy(Desk $desk): JsonResponse
    {
        $response = Http::timeout(3)->delete($this->deskServiceUrl . '/desks/' . $desk->id);

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to delete desk'], 502);
        }

        return response()->json(['message' => 'Desk deleted successfully']);
    }

    /**
     * Delete all desks via the Go microservice.
     */
    public function destroyAll(): JsonResponse
    {
        $response = Http::timeout(3)->delete($this->deskServiceUrl . '/desks');

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to delete all desks'], 502);
        }

        return response()->json(['message' => 'All desks deleted successfully']);
    }

    /**
     * Reset groups and return a clean canvas state via the Go microservice.
     */
    public function newConfig(): JsonResponse
    {
        $response = Http::timeout(3)->post($this->deskServiceUrl . '/desks/new-config');

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to reset configuration'], 502);
        }

        return response()->json($response->json());
    }
}
