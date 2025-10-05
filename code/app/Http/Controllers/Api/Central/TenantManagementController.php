<?php

namespace App\Http\Controllers\Api\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Tenant Management",
 *     description="API endpoints for managing tenants in the central domain"
 * )
 */
class TenantManagementController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/central/tenants",
     *     summary="Create a new tenant",
     *     description="Creates a new tenant with its database, runs migrations, and assigns domains",
     *     operationId="createTenant",
     *     tags={"Tenant Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "domains"},
     *             @OA\Property(
     *                 property="id",
     *                 type="string",
     *                 description="Unique tenant identifier (alphanumeric, dashes, underscores)",
     *                 example="acme-corp"
     *             ),
     *             @OA\Property(
     *                 property="domains",
     *                 type="array",
     *                 description="List of domains to assign to the tenant",
     *                 @OA\Items(type="string"),
     *                 example={"acme.comandaflow.local", "acme.local"}
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Additional tenant data (name, plan, settings, etc.)",
     *                 @OA\Property(property="name", type="string", example="ACME Corporation"),
     *                 @OA\Property(property="plan", type="string", example="premium"),
     *                 @OA\Property(
     *                     property="settings",
     *                     type="object",
     *                     @OA\Property(property="timezone", type="string", example="America/Mexico_City"),
     *                     @OA\Property(property="currency", type="string", example="MXN"),
     *                     @OA\Property(property="language", type="string", example="es")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tenant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tenant created successfully"),
     *             @OA\Property(
     *                 property="tenant",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", example="acme-corp"),
     *                 @OA\Property(property="data", type="object"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="domains",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="domain", type="string"),
     *                         @OA\Property(property="tenant_id", type="string")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="database", type="string", example="tenantacme-corp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="array",
     *                     @OA\Items(type="string", example="The id has already been taken.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error creating tenant"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validated = $request->validate([
            'id' => [
                'required',
                'string',
                'max:255',
                'unique:tenants,id',
                'regex:/^[a-zA-Z0-9_-]+$/',
            ],
            'domains' => [
                'required',
                'array',
                'min:1',
            ],
            'domains.*' => [
                'required',
                'string',
                'max:255',
                'unique:domains,domain',
                'regex:/^[a-zA-Z0-9.-]+$/',
            ],
            'data' => [
                'nullable',
                'array',
            ],
            'data.name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'data.plan' => [
                'nullable',
                'string',
                'in:free,trial,basic,premium,enterprise',
            ],
            'data.settings' => [
                'nullable',
                'array',
            ],
        ]);

        try {
            // Create tenant (without transaction because CREATE DATABASE can't run inside one)
            $tenant = Tenant::create([
                'id' => $validated['id'],
                'data' => $validated['data'] ?? [],
            ]);

            // Create domains
            foreach ($validated['domains'] as $domain) {
                $tenant->domains()->create([
                    'domain' => $domain,
                ]);
            }

            // Reload with domains
            $tenant->load('domains');

            // NOTA: La creación de la base de datos y las migraciones
            // se ejecutan automáticamente mediante eventos (ver TenancyServiceProvider)
            // El job CreateDatabase se ejecuta de forma síncrona y crea la BD.
            // No se usa transacción porque PostgreSQL no permite CREATE DATABASE dentro de una.

            // Get database name
            $databaseName = config('tenancy.database.prefix', 'tenant') . $tenant->id;

            return response()->json([
                'message' => 'Tenant created successfully',
                'tenant' => $tenant,
                'database' => $databaseName,
                'info' => [
                    'migrations_run' => 'Tenant database created and migrations executed automatically',
                    'access_urls' => $tenant->domains->pluck('domain')->map(function ($domain) {
                        return 'https://' . $domain;
                    }),
                ],
            ], 201);

        } catch (\Exception $e) {
            // Note: If tenant was created but an error occurred, you may need to manually clean up
            // Consider implementing a cleanup mechanism or using a more sophisticated error handling
            
            return response()->json([
                'message' => 'Error creating tenant',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/central/tenants/{id}",
     *     summary="Get tenant details",
     *     description="Returns detailed information about a specific tenant",
     *     operationId="getTenant",
     *     tags={"Tenant Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tenant ID",
     *         required=true,
     *         @OA\Schema(type="string", example="acme-corp")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", example="acme-corp"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="domains",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="domain", type="string"),
     *                     @OA\Property(property="tenant_id", type="string")
     *                 )
     *             ),
     *             @OA\Property(property="database", type="string", example="tenantacme-corp")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tenant not found")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $tenant = Tenant::with('domains')->find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        // Get database name
        $databaseName = config('tenancy.database.prefix', 'tenant') . $tenant->id;

        return response()->json([
            'tenant' => $tenant,
            'database' => $databaseName,
            'access_urls' => $tenant->domains->pluck('domain')->map(function ($domain) {
                return 'https://' . $domain;
            }),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/central/tenants/{id}",
     *     summary="Update tenant information",
     *     description="Updates tenant data (name, plan, settings, etc.). Does not update domains.",
     *     operationId="updateTenant",
     *     tags={"Tenant Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tenant ID",
     *         required=true,
     *         @OA\Schema(type="string", example="acme-corp")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Tenant data to update",
     *                 @OA\Property(property="name", type="string", example="ACME Corporation Updated"),
     *                 @OA\Property(property="plan", type="string", example="enterprise"),
     *                 @OA\Property(
     *                     property="settings",
     *                     type="object",
     *                     @OA\Property(property="timezone", type="string", example="America/New_York")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tenant updated successfully"),
     *             @OA\Property(property="tenant", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        // Validate request
        $validated = $request->validate([
            'data' => [
                'required',
                'array',
            ],
            'data.name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'data.plan' => [
                'nullable',
                'string',
                'in:free,trial,basic,premium,enterprise',
            ],
            'data.settings' => [
                'nullable',
                'array',
            ],
        ]);

        try {
            // Merge existing data with new data
            $tenant->data = array_merge($tenant->data ?? [], $validated['data']);
            $tenant->save();

            $tenant->load('domains');

            return response()->json([
                'message' => 'Tenant updated successfully',
                'tenant' => $tenant,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating tenant',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/central/tenants/{id}",
     *     summary="Delete a tenant",
     *     description="Deletes a tenant, its domains, and its database. THIS ACTION IS IRREVERSIBLE!",
     *     operationId="deleteTenant",
     *     tags={"Tenant Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tenant ID",
     *         required=true,
     *         @OA\Schema(type="string", example="acme-corp")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tenant deleted successfully"),
     *             @OA\Property(property="tenant_id", type="string", example="acme-corp"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting tenant"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        try {
            $tenantId = $tenant->id;
            
            // Delete tenant (domains are deleted via foreign key cascade)
            // La eliminación de la base de datos se ejecuta automáticamente
            // mediante el evento TenantDeleted (ver TenancyServiceProvider)
            $tenant->delete();

            return response()->json([
                'message' => 'Tenant deleted successfully',
                'tenant_id' => $tenantId,
                'deleted_at' => now(),
                'warning' => 'All tenant data including database has been permanently deleted',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting tenant',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/central/tenants/{id}/domains",
     *     summary="Add domain to tenant",
     *     description="Adds a new domain to an existing tenant",
     *     operationId="addTenantDomain",
     *     tags={"Tenant Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tenant ID",
     *         required=true,
     *         @OA\Schema(type="string", example="acme-corp")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"domain"},
     *             @OA\Property(property="domain", type="string", example="newdomain.comandaflow.local")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Domain added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Domain added successfully"),
     *             @OA\Property(property="domain", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error (domain already exists)"
     *     )
     * )
     */
    public function addDomain(Request $request, string $id): JsonResponse
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        $validated = $request->validate([
            'domain' => [
                'required',
                'string',
                'max:255',
                'unique:domains,domain',
                'regex:/^[a-zA-Z0-9.-]+$/',
            ],
        ]);

        try {
            $domain = $tenant->domains()->create([
                'domain' => $validated['domain'],
            ]);

            return response()->json([
                'message' => 'Domain added successfully',
                'domain' => $domain,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding domain',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/central/tenants/{id}/domains/{domainId}",
     *     summary="Remove domain from tenant",
     *     description="Removes a domain from a tenant. Tenant must have at least one domain.",
     *     operationId="removeTenantDomain",
     *     tags={"Tenant Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Tenant ID",
     *         required=true,
     *         @OA\Schema(type="string", example="acme-corp")
     *     ),
     *     @OA\Parameter(
     *         name="domainId",
     *         in="path",
     *         description="Domain ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Domain removed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tenant or domain not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Cannot remove last domain"
     *     )
     * )
     */
    public function removeDomain(string $id, int $domainId): JsonResponse
    {
        $tenant = Tenant::with('domains')->find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found',
            ], 404);
        }

        // Check if tenant has more than one domain
        if ($tenant->domains->count() <= 1) {
            return response()->json([
                'message' => 'Cannot remove the last domain from a tenant',
                'error' => 'Tenant must have at least one domain',
            ], 422);
        }

        $domain = $tenant->domains()->find($domainId);

        if (!$domain) {
            return response()->json([
                'message' => 'Domain not found for this tenant',
            ], 404);
        }

        try {
            $domain->delete();

            return response()->json([
                'message' => 'Domain removed successfully',
                'domain_id' => $domainId,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error removing domain',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
