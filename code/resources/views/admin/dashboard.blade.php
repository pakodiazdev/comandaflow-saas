<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ComandaFlow SaaS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 3rem;
            max-width: 900px;
            width: 90%;
        }
        h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .link-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .link-card:hover {
            border-color: #667eea;
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
        }
        .link-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .link-description {
            font-size: 0.9rem;
            color: #666;
        }
        .badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏢 Admin Dashboard</h1>
        <p class="subtitle">Central Management - ComandaFlow SaaS</p>
        
        <div class="badge">✅ Stancl Tenancy v3.9.1 Active</div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value">{{ \App\Models\Tenant::count() }}</div>
                <div class="stat-label">Total Tenants</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ \Stancl\Tenancy\Database\Models\Domain::count() }}</div>
                <div class="stat-label">Total Domains</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ DB::connection('pgsql')->getDatabaseName() }}</div>
                <div class="stat-label">Central DB</div>
            </div>
        </div>

        <div class="links">
            <a href="/api/internal/swagger/central" class="link-card">
                <div class="link-title">📚 API Documentation</div>
                <div class="link-description">Swagger/OpenAPI Central API docs</div>
            </a>
            
            <a href="/api/v1/central/tenants" class="link-card">
                <div class="link-title">🏢 Tenants API</div>
                <div class="link-description">List all tenants (JSON)</div>
            </a>
            
            <a href="/api/v1/health" class="link-card">
                <div class="link-title">💚 Health Check</div>
                <div class="link-description">System health status</div>
            </a>
            
            <a href="https://pgadmin.comandaflow.local" class="link-card">
                <div class="link-title">🗄️ PgAdmin</div>
                <div class="link-description">Database management</div>
            </a>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; text-align: center; color: #666; font-size: 0.9rem;">
            <p><strong>Environment:</strong> {{ config('app.env') }} | <strong>Laravel:</strong> {{ app()->version() }} | <strong>PHP:</strong> {{ phpversion() }}</p>
        </div>
    </div>
</body>
</html>
