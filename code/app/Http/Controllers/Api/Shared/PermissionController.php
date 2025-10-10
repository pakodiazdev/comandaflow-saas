<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use CF\CE\Auth\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/permissions",
     *     operationId="getPermissions",
     *     tags={"Roles & Permissions"},
     *     summary="List all permissions",
     *     description="Get a list of all available permissions in the system",
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
        $perPage = $request->input('per_page', 50);
        $search = $request->input('search');

        $query = Permission::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $permissions = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $permissions->items(),
            'pagination' => [
                'total' => $permissions->total(),
                'per_page' => $permissions->perPage(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'from' => $permissions->firstItem(),
                'to' => $permissions->lastItem(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/permissions/{id}",
     *     operationId="getPermission",
     *     tags={"Roles & Permissions"},
     *     summary="Get a specific permission",
     *     description="Get details of a specific permission",
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
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'description' => $permission->description,
            ]
        ]);
    }
}
