<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ComandaFlow Tenant API",
 *     description="Tenant-specific API for restaurant/business operations including orders, products, inventory, and reports. Each tenant has isolated data and authentication. Authenticate using POST /api/v1/auth/login with tenant-specific credentials.",
 *     @OA\Contact(
 *         email="support@comandaflow.com",
 *         name="ComandaFlow Support"
 *     ),
 *     @OA\License(
 *         name="Proprietary",
 *         url="https://comandaflow.com/license"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Tenant API Server - Automatically uses current domain"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token obtained from POST /api/v1/auth/login. Format: Bearer {your-token}"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Tenant user authentication and authorization endpoints"
 * )
 */
class TenantBaseController extends Controller
{
    //
}

