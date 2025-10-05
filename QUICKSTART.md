# 🚀 ComandaFlow SaaS Backend - Quick Start

## ✅ Estado del Proyecto

### 🎯 Completado
- ✅ **Stancl Tenancy v3.9.1** - Sistema multi-tenant completamente configurado
- ✅ **Swagger/OpenAPI** - Documentación de API automática
- ✅ **SSL/TLS** - Certificados HTTPS con mkcert para desarrollo local
- ✅ **Docker** - Stack completo (PHP 8.2, Nginx, PostgreSQL, MailHog, PgAdmin)

## 🌐 URLs de Acceso

### 🔒 HTTPS (Recomendado - Requiere instalar rootCA)
- **Central API**: https://comandaflow.local
- **Swagger UI Central**: https://comandaflow.local/api/internal/swagger/central
- **Swagger UI Tenant**: https://comandaflow.local/api/internal/swagger/tenant
- **PgAdmin**: https://pgadmin.comandaflow.local
- **MailHog**: http://localhost:8025 (solo HTTP disponible)

### 🔓 HTTP (Sin certificados)
- **Central API**: http://localhost
- **PgAdmin**: http://localhost:8082
- **MailHog**: http://localhost:8025
- **PostgreSQL**: localhost:5433

> **Nota**: Los puertos HTTP (80) y HTTPS (443) son configurables desde el archivo `.env` con las variables `WEB_PORT` y `WEB_HTTPS_PORT`

## 🔐 Instalar Certificado Root CA

Para usar HTTPS sin warnings de seguridad, instala el certificado raíz:

### 🍎 macOS
```bash
# Instalar en el keychain del sistema
sudo security add-trusted-cert -d -r trustRoot \
  -k /Library/Keychains/System.keychain \
  docker/cert/rootCA.crt

# Reiniciar el navegador
```

### 🪟 Windows
1. Doble clic en `docker/cert/rootCA.crt`
2. Clic en "Install Certificate..."
3. Seleccionar "Local Machine" → Next
4. Seleccionar "Place all certificates in the following store"
5. Browse → "Trusted Root Certification Authorities"
6. Next → Finish
7. Reiniciar el navegador

### 🐧 Linux (Ubuntu/Debian)
```bash
# Copiar certificado
sudo cp docker/cert/rootCA.crt /usr/local/share/ca-certificates/

# Actualizar certificados
sudo update-ca-certificates

# Reiniciar navegador
```

📖 **Guía completa**: Ver `docker/cert/README.md`

## 🧪 Probar Tenancy

### Crear un tenant de prueba
```bash
docker-compose exec php php artisan tinker

# En tinker:
$tenant = \App\Models\Tenant::create(['id' => 'test-tenant']);
echo "Tenant creado: " . $tenant->id;
exit;
```

### Verificar base de datos del tenant
```bash
# Listar bases de datos
docker-compose exec postgres psql -U postgres -c "\l"

# Verificar tablas del tenant
docker-compose exec postgres psql -U postgres -d tenanttest-tenant -c "\dt"
```

### Acceder al tenant
- **HTTPS**: https://test-tenant.comandaflow.local
- **HTTP**: http://test-tenant.comandaflow.local
- **Hosts**: Agregar en `/etc/hosts`: `127.0.0.1 test-tenant.comandaflow.local`

## 📝 Comandos Útiles

### Docker
```bash
# Iniciar servicios
docker-compose up -d

# Reconstruir con cambios
docker-compose up -d --build

# Ver logs
docker-compose logs -f web
docker-compose logs -f php

# Detener servicios
docker-compose down

# Limpiar todo (incluyendo volúmenes)
docker-compose down -v
```

### Laravel
```bash
# Entrar al contenedor PHP
docker-compose exec php bash

# Artisan commands
docker-compose exec php php artisan migrate
docker-compose exec php php artisan tinker
docker-compose exec php php artisan tenancy:list

# Limpiar caches
docker-compose exec php php artisan optimize:clear

# Regenerar documentación Swagger
docker-compose exec php php artisan l5-swagger:generate
```

### Composer
```bash
# Instalar dependencias
docker-compose exec php composer install

# Actualizar dependencias
docker-compose exec php composer update

# Agregar paquete
docker-compose exec php composer require vendor/package
```

## 🗄️ Acceso a Base de Datos

### Credenciales PostgreSQL
- **Host**: localhost
- **Puerto**: 5433
- **Base de datos**: commandaflow_saas
- **Usuario**: postgres
- **Contraseña**: secret

### PgAdmin
- **URL**: http://localhost:8082
- **Email**: admin@admin.com
- **Contraseña**: secret

## 📚 Documentación API

### Swagger UI
- **Central API**: https://comandaflow.local:8443/api/internal/swagger/central
- **JSON**: https://comandaflow.local:8443/api/internal/swagger/central/docs

### Agregar endpoints
Crea controladores en:
- `code/app/Http/Controllers/Api/Central/` - API central
- `code/app/Http/Controllers/Api/Tenant/` - API de tenants
- `code/app/Http/Controllers/Api/Shared/` - API compartida

Usa anotaciones OpenAPI:
```php
/**
 * @OA\Get(
 *     path="/api/v1/endpoint",
 *     tags={"Tag"},
 *     @OA\Response(response=200, description="Success")
 * )
 */
public function index() { }
```

Regenera documentación:
```bash
docker-compose exec php php artisan l5-swagger:generate
```

## 🔄 Próximos Pasos

1. ⏳ **Configurar Queue Worker** - Cambiar a jobs asíncronos
2. 🎨 **API de Gestión de Tenants** - CRUD completo para tenants
3. 🔐 **Autenticación** - Laravel Sanctum/Passport
4. 📊 **Dashboard Admin** - Panel de control central
5. 🧪 **Tests** - PHPUnit/Pest para testing

## 🆘 Troubleshooting

### El navegador muestra "Not Secure"
→ Instala el certificado rootCA siguiendo las instrucciones arriba

### No puedo acceder a comandaflow.local
→ Agrega a `/etc/hosts` (macOS/Linux) o `C:\Windows\System32\drivers\etc\hosts` (Windows):
```
127.0.0.1 comandaflow.local
127.0.0.1 pgadmin.comandaflow.local
```

### Error "Connection refused" en puerto 8443
→ Verifica que el contenedor web esté corriendo:
```bash
docker-compose ps
docker-compose logs web
```

### Las migraciones no se ejecutan en tenant
→ Verifica que TenancyServiceProvider esté registrado en `code/bootstrap/providers.php`

### Certificados expirados
```bash
# Detener contenedores
docker-compose down

# Eliminar volumen de certificados
docker volume rm cf-saas-backend_nginx_certs

# Reiniciar (se regenerarán automáticamente)
docker-compose up -d
```

## 📋 Estructura del Proyecto

```
cf-saas-backend/
├── code/                          # Código Laravel
│   ├── app/
│   │   ├── Http/Controllers/Api/  # Controladores API
│   │   ├── Models/                # Modelos (Tenant, etc.)
│   │   └── Providers/             # Service Providers
│   ├── config/                    # Configuraciones
│   ├── database/
│   │   └── migrations/
│   │       ├── tenant/            # Migraciones de tenant
│   │       └── ...                # Migraciones centrales
│   └── routes/
│       ├── api.php                # Rutas API central
│       └── tenant.php             # Rutas tenant
├── docker/
│   ├── cert/                      # Certificados SSL
│   │   ├── rootCA.crt             # Root CA (persistente)
│   │   └── README.md              # Guía de instalación
│   ├── nginx/
│   │   ├── conf.d/                # Configuraciones nginx
│   │   ├── init/                  # Scripts de inicialización
│   │   └── Dockerfile
│   └── php/
│       └── Dockerfile
└── docker-compose.yml             # Stack completo

```

---

**Última actualización**: 5 de octubre de 2025  
**Branch**: feature/fresh-tenancy-setup  
**Laravel**: 12.32.5 | **PHP**: 8.2 | **PostgreSQL**: 15
