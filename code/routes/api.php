<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Always include shared routes (available in both central and tenant contexts)
require __DIR__.'/api-shared.php';

// Always include central routes (will be available in central context)
require __DIR__.'/api-central.php';

// Tenant routes are loaded separately via routes/tenant.php and Stancl Tenancy
