<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Staff/Index', [
            'users' => User::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Staff/Create');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'is_active' => $request->input('is_active'),
            'password' => Hash::make($request->input('password')),
        ]);
        Log::info('User created: ', ['user' => $user]);

        // event(new Registered($user));

        return redirect()->route('profile.index');
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        Log::info('User delete attempt: ', ['user' => $user]);
        if (Auth::user()->id === $user->id) {
            return Redirect::route('profile.index')->with('error', 'You cannot delete yourself');
        }

        Log::info('User deleted: ', ['user' => $user]);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function editAdmin(User $user): Response
    {
        return Inertia::render('Staff/Edit', [
            'user' => $user,
        ]);

    }

    public function updateAdmin(Request $request, User $user): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'is_active' => $request->input('is_active'),
        ]);

        return redirect()->route('profile.index');
    }

    public function fetchUsers()
    {
        return response()->json(User::all());
    }
}
