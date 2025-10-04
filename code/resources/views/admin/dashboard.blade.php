<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Admin - ComandaFlow</title>
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
        .title {
            font-size: 2.5em;
            margin: 0;
            color: #333;
        }
        .subtitle {
            color: #666;
            margin: 5px 0;
        }
        .tenants {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .tenant-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .tenant-name {
            font-size: 1.5em;
            margin: 0 0 10px 0;
            color: #333;
            text-transform: capitalize;
        }
        .tenant-domain {
            color: #666;
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 5px 5px 0;
            font-size: 0.9em;
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">ComandaFlow Central Admin</h1>
        <p class="subtitle">Manage your multi-tenant SaaS platform</p>
    </div>

    <div class="tenants">
        <div class="tenant-card">
            <h3 class="tenant-name">SushiGo</h3>
            <p class="tenant-domain">sushigo.comandaflow.local</p>
            <p>Japanese restaurant management system</p>
            <a href="http://sushigo.comandaflow.local" class="btn" target="_blank">Visit Tenant</a>
            <a href="#" class="btn btn-secondary">Manage</a>
        </div>

        <div class="tenant-card">
            <h3 class="tenant-name">RealBurger</h3>
            <p class="tenant-domain">realburger.comandaflow.local</p>
            <p>American burger restaurant system</p>
            <a href="http://realburger.comandaflow.local" class="btn" target="_blank">Visit Tenant</a>
            <a href="#" class="btn btn-secondary">Manage</a>
        </div>

        <div class="tenant-card">
            <h3 class="tenant-name">DanielsWinds</h3>
            <p class="tenant-domain">danielswinds.comandaflow.local</p>
            <p>Wind energy company management</p>
            <a href="http://danielswinds.comandaflow.local" class="btn" target="_blank">Visit Tenant</a>
            <a href="#" class="btn btn-secondary">Manage</a>
        </div>
    </div>

    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 20px;">
        <h2>System Information</h2>
        <p><strong>Central Domain:</strong> comandaflow.local</p>
        <p><strong>Tenant Domains:</strong> *.comandaflow.local</p>
        <p><strong>Database Strategy:</strong> Separate database per tenant</p>
        <p><strong>Status:</strong> Multi-tenancy enabled</p>
    </div>
</body>
</html>
