<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Desk;
use App\Models\Group;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function orderIndex(Group $group): View
    {
        $orders = Order::where('group_id', $group->id)->paginate(9);

        return view('orders.index', ['orders' => $orders]);
    }

    public function create(): View
    {
        return view('groups.create', ['desks' => Desk::where('joined_at', null)->get()]);
    }

    public function store(GroupRequest $request): JsonResponse
    {

        $group = Group::create();

        $group->desks()->attach($request->input('desks'));

        Desk::whereIn('id', $request->input('desks'))->update(['joined_at' => now()]);

        return response()->json(['id' => $group->id], 201);
    }

    public function edit(Group $group): View
    {
        return view('groups.edit', [
            'group' => $group,
            'desks' => $group->desks,
        ]);
    }

    public function destroy(Group $group): RedirectResponse
    {
        Order::where('group_id', $group->id)->update(['group_id' => null]);

        $group->desks()->update(['joined_at' => null]);
        $group->desks()->detach();
        $group->delete();

        return redirect('/desks')->with('success', 'Group deleted successfully');
    }

    public function generateCode(Group $group): RedirectResponse
    {
        $group->update(['code' => fake()->randomNumber(5, true)]);
        $group->desks()->update(['is_occupied' => true]);

        return back()->with('success', "Code for Desk#$group->id generated successfully");
    }

    public function clearCode(Group $group): RedirectResponse
    {
        $group->update(['code' => null]);
        $group->desks()->update(['is_occupied' => false]);

        return back()->with('success', "Code for Desk#$group->id cleared successfully");
    }

    public static function freeInactiveGroups(): void
    {
        $groups = Group::whereHas('desks', function (Builder $query) {
            $query->where('is_occupied', true);
        })->has('orders')->get();

        foreach ($groups as $group) {
            if ($group->activeOrders()->count() === 0 && $group->latestOrder()->first()->updated_at->diffInUTCMinutes() > 30) {
                $group->update([
                    'code' => null,
                ]);
                $group->desks()->update(['is_occupied' => false]);
            }
        }
    }
}
