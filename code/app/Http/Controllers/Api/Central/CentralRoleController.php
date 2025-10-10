<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Api\Shared\RoleController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Central Role Controller
 * 
 * This controller duplicates role endpoints under /api/v1/central/roles
 * for reverse proxy compatibility while delegating to the base RoleController.
 * 
 * @OA\Tag(
 *     name="Central Roles & Permissions",
 *     description="Central-specific endpoints for managing roles and permissions"
 * )
 */
class CentralRoleController extends RoleController
{
    /**
     * @OA\Get(
     *     path="/api/v1/central/roles",
     *     operationId="getCentralRoles",
     *     tags={"Central Roles & Permissions"},
     *     summary="List all central roles",
     *     description="Get a list of all central roles with their permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Roles retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Super Administrator"),
     *                     @OA\Property(property="guard_name", type="string", example="api"),
     *                     @OA\Property(property="description", type="string", example="Full system access"),
     *                     @OA\Property(property="permissions", type="array", @OA\Items(type="string", example="users.view"))
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
     * @OA\Post(
     *     path="/api/v1/central/roles",
     *     operationId="createCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Create a new central role",
     *     description="Create a new central role with optional permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Manager"),
     *             @OA\Property(property="description", type="string", example="Store manager role"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string", example="products.view"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role created successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/central/roles/{id}",
     *     operationId="getCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Get a specific central role",
     *     description="Get details of a specific central role including its permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role retrieved successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        return parent::show($id);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/central/roles/{id}",
     *     operationId="updateCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Update a central role",
     *     description="Update an existing central role and its permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Senior Manager"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        return parent::update($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/central/roles/{id}",
     *     operationId="deleteCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Delete a central role",
     *     description="Delete an existing central role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        return parent::destroy($id);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/central/roles/{id}/permissions",
     *     operationId="assignPermissionsToCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Assign permissions to central role",
     *     description="Add permissions to an existing central role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permissions"},
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string", example="products.view"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissions assigned successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function assignPermissions(Request $request, int $id): JsonResponse
    {
        return parent::assignPermissions($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/central/roles/{id}/permissions/{permissionName}",
     *     operationId="removePermissionFromCentralRole",
     *     tags={"Central Roles & Permissions"},
     *     summary="Remove permission from central role",
     *     description="Remove a specific permission from a central role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="permissionName",
     *         in="path",
     *         description="Permission name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission removed successfully"
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role or permission not found")
     * )
     */
    public function removePermission(int $id, string $permissionName): JsonResponse
    {
        return parent::removePermission($id, $permissionName);
    }
}
