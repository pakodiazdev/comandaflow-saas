<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ComandaFlow Tenant API Documentation",
 *     description="Tenant-specific API for business operations",
 *     @OA\Contact(
 *         email="support@comandaflow.com",
 *         name="ComandaFlow Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Tenant API - Automatically uses current URL"
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
class TenantBaseController extends Controller
{
    //
}

