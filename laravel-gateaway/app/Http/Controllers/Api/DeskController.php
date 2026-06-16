<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeskApiRequest;
use App\Models\Desk;
use App\Models\Group;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class DeskController extends Controller
{
    public function index(): JsonResponse
    {
        $groups = Group::with('desks')->get();
        $standaloneDesks = Desk::whereNull('joined_at')->get();

        return response()->json([
            'desksProp' => [
                'groups' => $groups,
                'standaloneDesks' => $standaloneDesks,
            ],
            'rooms' => [],
        ]);
    }

    public function store(DeskApiRequest $request): JsonResponse
    {
        $desks = $request->input('desks');

        foreach ($desks as $desk) {
            Desk::updateOrCreate(
                ['id' => $desk['id'] ?? null],
                [
                    'x' => $desk['x'],
                    'y' => $desk['y'],
                    'nrOfSeats' => $desk['nrOfSeats'],
                ]
            );
        }

        return response()->json([
            'message' => 'Data received successfully',
            'data' => $request->input('desks'),
        ]);
    }

    public function destroy(Desk $desk): JsonResponse
    {
        $desk->delete();

        return response()->json([
            'message' => 'Desk deleted successfully',
        ], 200);
    }

    public function destroyAll(): JsonResponse
    {
        $desks = Desk::all();

        foreach ($desks as $desk) {
            $desk->delete();
        }

        return response()->json([
            'message' => 'All desks deleted successfully',
        ], 200);
    }

    public function newConfig(): JsonResponse
    {
        $groups = Group::with('desks.orders')->get();

        Order::whereIn('group_id', $groups->pluck('id'))->update(['group_id' => null]);

        foreach ($groups as $group) {
            $group->desks()->update(['joined_at' => null]);
            $group->desks()->detach();
            $group->delete();
        }

        return response()->json([
            'desksProp' => [
                'groups' => [],
                'standaloneDesks' => Desk::whereNull('joined_at')->get(),
            ],
            'rooms' => [],
        ]);
    }
}
