<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $tenant->id ?? 'Tenant' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .tenant-name {
            font-size: 2em;
            margin: 0;
            color: #333;
            text-transform: capitalize;
        }
        .domain {
            color: #666;
            margin: 5px 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin: 0;
        }
        .stat-label {
            color: #666;
            margin: 5px 0 0 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background: #5a6fd8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="tenant-name">{{ $tenant->id ?? 'Dashboard' }}</h1>
        <p class="domain">{{ $domain }}</p>
        <a href="/" class="btn">Home</a>
        <a href="http://comandaflow.local" class="btn">Central Admin</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <p class="stat-number">0</p>
            <p class="stat-label">Active Projects</p>
        </div>
        <div class="stat-card">
            <p class="stat-number">0</p>
            <p class="stat-label">Team Members</p>
        </div>
        <div class="stat-card">
            <p class="stat-number">0h</p>
            <p class="stat-label">Hours Tracked</p>
        </div>
        <div class="stat-card">
            <p class="stat-number">0</p>
            <p class="stat-label">Tasks Completed</p>
        </div>
    </div>

    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2>Welcome to your ComandaFlow workspace!</h2>
        <p>This is your dedicated tenant environment. Your data is completely isolated from other tenants.</p>
        <p><strong>Tenant ID:</strong> {{ $tenant->id ?? 'N/A' }}</p>
        <p><strong>Domain:</strong> {{ $domain }}</p>
        <p><strong>Database:</strong> {{ config('database.connections.tenant.database') ?? 'tenant_' . ($tenant->id ?? 'default') }}</p>
    </div>
</body>
</html>
