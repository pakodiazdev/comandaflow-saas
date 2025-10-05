<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;

/**
 * @OA\Tag(
 *     name="Tenant",
 *     description="Tenant-specific operations"
 * )
 */
class TenantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tenant/info",
     *     summary="Get tenant information",
     *     description="Returns information about the current tenant",
     *     operationId="getTenantInfo",
     *     tags={"Tenant"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="tenant_id", type="string", example="sushigo"),
     *             @OA\Property(property="domain", type="string", example="sushigo.comandaflow.local"),
     *             @OA\Property(property="database", type="string", example="tenant_sushigo"),
     *             @OA\Property(property="is_tenant", type="boolean", example=true),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function info(Request $request)
    {
        $tenant = tenancy()->tenant;
        
        if (!$tenant) {
            return response()->json([
                'error' => 'No tenant context found',
                'is_tenant' => false,
                'domain' => $request->getHost()
            ], 404);
        }

        return response()->json([
            'tenant_id' => $tenant->id,
            'domain' => $request->getHost(),
            'database' => $tenant->database()->getName(),
            'is_tenant' => true,
            'created_at' => $tenant->created_at,
            'updated_at' => $tenant->updated_at,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tenant/health",
     *     summary="Get tenant health status",
     *     description="Returns health status of the current tenant",
     *     operationId="getTenantHealth",
     *     tags={"Tenant"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="healthy"),
     *             @OA\Property(property="tenant_id", type="string", example="sushigo"),
     *             @OA\Property(property="database_connected", type="boolean", example=true),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function health(Request $request)
    {
        $tenant = tenancy()->tenant;
        
        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'error' => 'No tenant context found',
                'timestamp' => now()
            ], 404);
        }

        try {
            // Test database connection
            $tenant->database()->getConnection()->getPdo();
            $databaseConnected = true;
        } catch (\Exception $e) {
            $databaseConnected = false;
        }

        return response()->json([
            'status' => $databaseConnected ? 'healthy' : 'unhealthy',
            'tenant_id' => $tenant->id,
            'database_connected' => $databaseConnected,
            'timestamp' => now()
        ]);
    }
}
