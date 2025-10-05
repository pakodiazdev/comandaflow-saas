<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

/**
 * @OA\Tag(
 *     name="Central",
 *     description="Central domain operations"
 * )
 */
class CentralController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/central/tenants",
     *     summary="Get all tenants",
     *     description="Returns a list of all tenants in the system",
     *     operationId="getAllTenants",
     *     tags={"Central"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="string", example="sushigo"),
     *                 @OA\Property(property="data", type="object"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function tenants(Request $request)
    {
        $tenants = Tenant::with('domains')->get();
        
        return response()->json([
            'tenants' => $tenants,
            'total' => $tenants->count(),
            'timestamp' => now()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/central/stats",
     *     summary="Get central system statistics",
     *     description="Returns statistics about the central system",
     *     operationId="getCentralStats",
     *     tags={"Central"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_tenants", type="integer", example=3),
     *             @OA\Property(property="active_tenants", type="integer", example=3),
     *             @OA\Property(property="system_status", type="string", example="operational"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function stats(Request $request)
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('created_at', '>', now()->subDays(30))->count();
        
        return response()->json([
            'total_tenants' => $totalTenants,
            'active_tenants' => $activeTenants,
            'system_status' => 'operational',
            'timestamp' => now()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/central/health",
     *     summary="Get central system health",
     *     description="Returns health status of the central system",
     *     operationId="getCentralHealth",
     *     tags={"Central"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="healthy"),
     *             @OA\Property(property="database_connected", type="boolean", example=true),
     *             @OA\Property(property="is_central", type="boolean", example=true),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function health(Request $request)
    {
        try {
            // Test central database connection
            \DB::connection('central')->getPdo();
            $databaseConnected = true;
        } catch (\Exception $e) {
            $databaseConnected = false;
        }

        return response()->json([
            'status' => $databaseConnected ? 'healthy' : 'unhealthy',
            'database_connected' => $databaseConnected,
            'is_central' => true,
            'timestamp' => now()
        ]);
    }
}
