<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register tenant routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "tenant" middleware group.
|
*/

Route::get('/', function () {
    return view('tenant.welcome', [
        'tenant' => tenant(),
        'domain' => request()->getHost()
    ]);
});

Route::get('/dashboard', function () {
    return view('tenant.dashboard', [
        'tenant' => tenant(),
        'domain' => request()->getHost()
    ]);
})->name('tenant.dashboard');

