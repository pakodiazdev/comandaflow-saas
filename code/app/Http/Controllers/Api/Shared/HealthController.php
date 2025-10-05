<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

class HealthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/health",
     *     summary="Health check",
     *     description="Returns the status of the application and its dependencies",
     *     tags={"System"},
     *     @OA\Response(
     *         response=200,
     *         description="System is healthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="healthy"),
     *             @OA\Property(property="timestamp", type="string", format="date-time", example="2023-10-04T02:12:00.000000Z"),
     *             @OA\Property(property="environment", type="string", example="local"),
     *             @OA\Property(property="version", type="string", example="1.0.0"),
     *             @OA\Property(property="tenant", type="object",
     *                 @OA\Property(property="context", type="string", example="tenant"),
     *                 @OA\Property(property="id", type="string", example="sushigo"),
     *                 @OA\Property(property="domain", type="string", example="sushigo.comandaflow.local")
     *             ),
     *             @OA\Property(property="checks", type="object",
     *                 @OA\Property(property="database", type="object",
     *                     @OA\Property(property="status", type="string", example="healthy"),
     *                     @OA\Property(property="message", type="string", example="Database connection successful")
     *                 ),
     *                 @OA\Property(property="cache", type="object",
     *                     @OA\Property(property="status", type="string", example="healthy"),
     *                     @OA\Property(property="message", type="string", example="Cache system working")
     *                 )
     *             ),
     *             @OA\Property(property="uptime", type="object",
     *                 @OA\Property(property="started_at", type="string", format="date-time"),
     *                 @OA\Property(property="uptime_seconds", type="integer", example=3600)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=503,
     *         description="System is unhealthy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="unhealthy"),
     *             @OA\Property(property="timestamp", type="string", format="date-time"),
     *             @OA\Property(property="environment", type="string", example="local"),
     *             @OA\Property(property="version", type="string", example="1.0.0"),
     *             @OA\Property(property="checks", type="object")
     *         )
     *     )
     * )
     */
    public function check(): JsonResponse
    {
        $status = 'healthy';
        $checks = [];
        $timestamp = now()->toISOString();

        // Check database connection
        try {
            DB::connection()->getPdo();
            $checks['database'] = [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            $status = 'unhealthy';
            $checks['database'] = [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }

        // Check cache connection
        try {
            Cache::put('health_check', 'ok', 10);
            $cacheValue = Cache::get('health_check');
            if ($cacheValue === 'ok') {
                $checks['cache'] = [
                    'status' => 'healthy',
                    'message' => 'Cache system working'
                ];
            } else {
                $status = 'unhealthy';
                $checks['cache'] = [
                    'status' => 'unhealthy',
                    'message' => 'Cache system not working properly'
                ];
            }
        } catch (\Exception $e) {
            $status = 'unhealthy';
            $checks['cache'] = [
                'status' => 'unhealthy',
                'message' => 'Cache system failed: ' . $e->getMessage()
            ];
        }

        // Check if we're in tenant context
        $tenantInfo = null;
        if (tenancy()->initialized) {
            $tenant = tenant();
            $tenantInfo = [
                'id' => $tenant->id,
                'domain' => request()->getHost(),
                'context' => 'tenant'
            ];
        } else {
            $tenantInfo = [
                'context' => 'central',
                'domain' => request()->getHost()
            ];
        }

        $response = [
            'status' => $status,
            'timestamp' => $timestamp,
            'environment' => app()->environment(),
            'version' => config('app.version', '1.0.0'),
            'tenant' => $tenantInfo,
            'checks' => $checks,
            'uptime' => [
                'started_at' => config('app.started_at', now()->toISOString()),
                'uptime_seconds' => time() - strtotime(config('app.started_at', now()->toISOString()))
            ]
        ];

        $httpStatus = $status === 'healthy' ? 200 : 503;

        return response()->json($response, $httpStatus);
    }
}
