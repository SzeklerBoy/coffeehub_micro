<?php

use App\Http\Controllers\DeskController;
use App\Http\Controllers\GroupController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('free:desks', function () {
    DeskController::freeInactiveDesks();
    GroupController::freeInactiveGroups();
})->purpose('Free up inactive desks')->everyTenMinutes()->withoutOverlapping();
