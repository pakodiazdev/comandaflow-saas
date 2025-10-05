<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ComandaFlow Central API Documentation",
 *     description="Central domain API for managing tenants and system-wide operations",
 *     @OA\Contact(
 *         email="support@comandaflow.com",
 *         name="ComandaFlow Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Central API - Automatically uses current URL"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 */
class CentralBaseController extends Controller
{
    //
}

