<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->id }} - ComandaFlow SaaS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .info-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 500;
            word-break: break-all;
        }
        
        .features {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
        
        .features h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        
        .feature-item:hover {
            transform: translateY(-2px);
            background: #e9ecef;
        }
        
        .feature-icon {
            font-size: 24px;
            margin-right: 12px;
        }
        
        .feature-text {
            font-size: 14px;
            color: #555;
        }
        
        .links {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🍱</div>
            <h1>Bienvenido a {{ $tenant->id }}</h1>
            <p class="subtitle">Sistema Multi-Tenant - ComandaFlow SaaS</p>
            <span class="status">✓ Activo</span>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Tenant ID</div>
                <div class="info-value">{{ $tenant->id }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Dominio Actual</div>
                <div class="info-value">{{ $domain }}</div>
            </div>
            
            @if($tenant->data && isset($tenant->data['name']))
            <div class="info-card">
                <div class="info-label">Nombre</div>
                <div class="info-value">{{ $tenant->data['name'] }}</div>
            </div>
            @endif
            
            @if($tenant->data && isset($tenant->data['plan']))
            <div class="info-card">
                <div class="info-label">Plan</div>
                <div class="info-value">{{ ucfirst($tenant->data['plan']) }}</div>
            </div>
            @endif
            
            <div class="info-card">
                <div class="info-label">Base de Datos</div>
                <div class="info-value">{{ config('tenancy.database.prefix') }}{{ $tenant->id }}</div>
            </div>
            
            <div class="info-card">
                <div class="info-label">Creado</div>
                <div class="info-value">{{ $tenant->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
        
        <div class="features">
            <h2>Características Disponibles</h2>
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">🔒</span>
                    <span class="feature-text">Datos Aislados</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🗄️</span>
                    <span class="feature-text">Base de Datos Propia</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🚀</span>
                    <span class="feature-text">API REST</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <span class="feature-text">Reportes</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔔</span>
                    <span class="feature-text">Notificaciones</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⚙️</span>
                    <span class="feature-text">Configuración</span>
                </div>
            </div>
        </div>
        
        <div class="links">
            <a href="/dashboard" class="btn btn-primary">📊 Dashboard</a>
            <a href="/api/v1/tenant/info" class="btn btn-secondary">🔍 API Info</a>
            <a href="/api/internal/swagger/tenant" class="btn btn-secondary">📚 API Docs</a>
        </div>
    </div>
</body>
</html>
