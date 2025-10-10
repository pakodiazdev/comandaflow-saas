<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ComandaFlow Central API",
 *     description="Central domain API for managing tenants, system configuration, and administrative operations. Authenticate using POST /api/v1/auth/login to obtain a Bearer token.",
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
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Central API Server"
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
 *     description="User authentication and authorization endpoints (shared)"
 * )
 *
 * @OA\Tag(
 *     name="Central Authentication",
 *     description="Central-specific authentication endpoints for reverse proxy compatibility"
 * )
 *
 * @OA\Tag(
 *     name="Roles & Permissions",
 *     description="Shared endpoints for managing roles and permissions"
 * )
 *
 * @OA\Tag(
 *     name="Central Roles & Permissions",
 *     description="Central-specific endpoints for managing roles and permissions"
 * )
 */
class CentralBaseController extends Controller
{
    //
}

