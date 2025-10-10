<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\Shared\PermissionController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Central Permission Controller
 * 
 * This controller duplicates permission endpoints under /api/v1/central/permissions
 * for reverse proxy compatibility while delegating to the base PermissionController.
 */
class CentralPermissionController extends PermissionController
{
    /**
     * @OA\Get(
     *     path="/api/v1/central/permissions",
     *     operationId="getCentralPermissions",
     *     tags={"Central Roles & Permissions"},
     *     summary="List all central permissions",
     *     description="Get a list of all available permissions in the central system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=50)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search permissions by name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="users.view"),
     *                     @OA\Property(property="guard_name", type="string", example="api"),
     *                     @OA\Property(property="description", type="string", example="View users")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        return parent::index($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/central/permissions/{id}",
     *     operationId="getCentralPermission",
     *     tags={"Central Roles & Permissions"},
     *     summary="Get a specific central permission",
     *     description="Get details of a specific central permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="users.view"),
     *                 @OA\Property(property="guard_name", type="string", example="api"),
     *                 @OA\Property(property="description", type="string", example="View users")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Permission not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        return parent::show($id);
    }
}
