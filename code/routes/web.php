<?php

use Illuminate\Support\Facades\Route;

// Central domain routes
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Tenant routes
Route::middleware(['web', 'tenant'])->group(function () {
    require __DIR__.'/tenant.php';
});
