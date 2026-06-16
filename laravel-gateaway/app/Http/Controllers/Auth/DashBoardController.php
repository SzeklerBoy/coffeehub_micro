<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashBoardController
{
    public function index()
    {

        if (Auth::check()) {
            return Auth::user()->role === null
                ? Inertia::render('Admin/AdminDashboard')
                : Inertia::render('Staff/StaffDashboard');
        }

        return Inertia::render('Home');
    }
}
