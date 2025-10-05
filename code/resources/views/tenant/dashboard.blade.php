<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $tenant->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 600;
        }
        
        .tenant-name {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .welcome {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .welcome h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome p {
            color: #666;
            font-size: 16px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #667eea;
        }
        
        .stat-icon {
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 32px;
            color: #333;
            font-weight: 700;
        }
        
        .actions {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .actions h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            padding: 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid #e9ecef;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .action-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        
        .action-content {
            flex: 1;
        }
        
        .action-title {
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .action-desc {
            font-size: 12px;
            color: #888;
        }
        
        .info-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .info-section h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .info-table {
            width: 100%;
        }
        
        .info-table tr {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table td {
            padding: 15px 10px;
        }
        
        .info-table td:first-child {
            color: #888;
            font-weight: 600;
            width: 200px;
        }
        
        .info-table td:last-child {
            color: #333;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 10px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">🍱 ComandaFlow</div>
            <div class="tenant-name">{{ $tenant->id }} @ {{ $domain }}</div>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h1>Dashboard de {{ $tenant->id }}</h1>
            <p>Bienvenido al panel de control de tu tenant</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-label">Estado</div>
                <div class="stat-value" style="font-size: 20px; color: #10b981;">Activo</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🗄️</div>
                <div class="stat-label">Base de Datos</div>
                <div class="stat-value" style="font-size: 16px;">{{ config('tenancy.database.prefix') }}{{ $tenant->id }}</div>
            </div>
            
            @if($tenant->data && isset($tenant->data['plan']))
            <div class="stat-card">
                <div class="stat-icon">💎</div>
                <div class="stat-label">Plan</div>
                <div class="stat-value" style="font-size: 20px;">{{ ucfirst($tenant->data['plan']) }}</div>
            </div>
            @endif
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-label">Miembro desde</div>
                <div class="stat-value" style="font-size: 16px;">{{ $tenant->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
        
        <div class="actions">
            <h2>Acciones Rápidas</h2>
            <div class="action-grid">
                <a href="/" class="action-btn">
                    <span class="action-icon">🏠</span>
                    <div class="action-content">
                        <div class="action-title">Inicio</div>
                        <div class="action-desc">Volver a la página principal</div>
                    </div>
                </a>
                
                <a href="/api/v1/tenant/info" class="action-btn">
                    <span class="action-icon">ℹ️</span>
                    <div class="action-content">
                        <div class="action-title">Info API</div>
                        <div class="action-desc">Información del tenant</div>
                    </div>
                </a>
                
                <a href="/api/internal/swagger/tenant" class="action-btn">
                    <span class="action-icon">📚</span>
                    <div class="action-content">
                        <div class="action-title">Documentación</div>
                        <div class="action-desc">API Swagger Docs</div>
                    </div>
                </a>
                
                <a href="/api/v1/tenant/health" class="action-btn">
                    <span class="action-icon">💚</span>
                    <div class="action-content">
                        <div class="action-title">Health Check</div>
                        <div class="action-desc">Estado del sistema</div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Información del Tenant</h2>
            <table class="info-table">
                <tr>
                    <td>Tenant ID</td>
                    <td>{{ $tenant->id }}</td>
                </tr>
                <tr>
                    <td>Dominio</td>
                    <td>{{ $domain }}</td>
                </tr>
                @if($tenant->data && isset($tenant->data['name']))
                <tr>
                    <td>Nombre</td>
                    <td>{{ $tenant->data['name'] }}</td>
                </tr>
                @endif
                <tr>
                    <td>Base de Datos</td>
                    <td>{{ config('tenancy.database.prefix') }}{{ $tenant->id }}</td>
                </tr>
                <tr>
                    <td>Creado</td>
                    <td>{{ $tenant->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td>Actualizado</td>
                    <td>{{ $tenant->updated_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                @if($tenant->data && isset($tenant->data['settings']))
                <tr>
                    <td>Configuración</td>
                    <td>
                        @if(isset($tenant->data['settings']['timezone']))
                            <strong>Zona Horaria:</strong> {{ $tenant->data['settings']['timezone'] }}<br>
                        @endif
                        @if(isset($tenant->data['settings']['currency']))
                            <strong>Moneda:</strong> {{ $tenant->data['settings']['currency'] }}<br>
                        @endif
                        @if(isset($tenant->data['settings']['language']))
                            <strong>Idioma:</strong> {{ $tenant->data['settings']['language'] }}
                        @endif
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</body>
</html>
