<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Room::all());
    }

    public function store(StoreRoomRequest $request)
    {
        Room::create([
            'width' => $request->input('width'),
            'length' => $request->input('length'),
        ]);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update([
            'width' => $request->input('width'),
            'length' => $request->input('length'),
        ]);
    }
}
