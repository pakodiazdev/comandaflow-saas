<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use CF\CE\Auth\Models\Role;
use CF\CE\Auth\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Roles & Permissions",
 *     description="Endpoints for managing roles and permissions"
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/roles",
     *     operationId="getRoles",
     *     tags={"Roles & Permissions"},
     *     summary="List all roles",
     *     description="Get a list of all roles with their permissions",
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
        $perPage = $request->input('per_page', 15);
        $roles = Role::with('permissions')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $roles->items(),
            'pagination' => [
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/roles",
     *     operationId="createRole",
     *     tags={"Roles & Permissions"},
     *     summary="Create a new role",
     *     description="Create a new role with optional permissions",
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
     *             @OA\Property(property="message", type="string", example="Role created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Manager"),
     *                 @OA\Property(property="description", type="string", example="Store manager role"),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'api',
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/roles/{id}",
     *     operationId="getRole",
     *     tags={"Roles & Permissions"},
     *     summary="Get a specific role",
     *     description="Get details of a specific role including its permissions",
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
     *         description="Role retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Manager"),
     *                 @OA\Property(property="description", type="string", example="Store manager role"),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/roles/{id}",
     *     operationId="updateRole",
     *     tags={"Roles & Permissions"},
     *     summary="Update a role",
     *     description="Update an existing role and its permissions",
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
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) {
            $role->name = $request->name;
        }

        if ($request->has('description')) {
            $role->description = $request->description;
        }

        $role->save();

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/roles/{id}",
     *     operationId="deleteRole",
     *     tags={"Roles & Permissions"},
     *     summary="Delete a role",
     *     description="Delete an existing role",
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
     *         description="Role deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/roles/{id}/permissions",
     *     operationId="assignPermissionsToRole",
     *     tags={"Roles & Permissions"},
     *     summary="Assign permissions to role",
     *     description="Add permissions to an existing role",
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
     *         description="Permissions assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permissions assigned successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function assignPermissions(Request $request, int $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role->givePermissionTo($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned successfully',
            'data' => [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/roles/{id}/permissions/{permissionName}",
     *     operationId="removePermissionFromRole",
     *     tags={"Roles & Permissions"},
     *     summary="Remove permission from role",
     *     description="Remove a specific permission from a role",
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
     *         description="Permission removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission removed successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Role or permission not found")
     * )
     */
    public function removePermission(int $id, string $permissionName): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $permission = Permission::where('name', $permissionName)->first();

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        $role->revokePermissionTo($permission);

        return response()->json([
            'success' => true,
            'message' => 'Permission removed successfully',
            'data' => [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]
        ]);
    }
}
