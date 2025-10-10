# pgAdmin Configuration Automatizada

Este directorio contiene la configuración automatizada para pgAdmin con servidores preconfigurados y autenticación automática.

## 🚀 Características implementadas:

✅ **Servidor PostgreSQL preconfigurado**
✅ **Autenticación automática** (sin pedir contraseñas)
✅ **Sin contraseña maestra** (master password)
✅ **Configuración desde variables de entorno**
✅ **Persistencia de datos** en volumen Docker
✅ **Modo desktop** simplificado

## 📁 Archivos incluidos:

- **servers.json**: Configuración de servidor PostgreSQL preconfigurado
- **entrypoint.sh**: Script personalizado que configura credenciales automáticamente
- **pgpass**: Archivo de contraseñas (generado dinámicamente)
- **init.sh**: Script auxiliar de inicialización

## 🔧 Configuración automática:

### Servidor preconfigurado:
- **Nombre**: ComandaFlow PostgreSQL (Main DB)
- **Grupo**: ComandaFlow Servers
- **Host**: postgres (contenedor)
- **Puerto**: 5432
- **Base de datos**: `${DB_NAME}` (desde .env)
- **Usuario**: `${DB_USER}` (desde .env)
- **Contraseña**: `${DB_PASS}` (desde .env)

## 🌐 Acceso:

- **URL**: http://localhost:8082 (o puerto configurado en PGADMIN_PORT)
- **Email**: `${PGADMIN_EMAIL}` (default: admin@admin.com)
- **Contraseña**: `${PGADMIN_PASS}` (default: secret)

## ⚙️ Variables de entorno (.env):

```env
# Puerto de pgAdmin
PGADMIN_PORT=8082

# Credenciales de acceso a pgAdmin
PGADMIN_EMAIL=admin@admin.com
PGADMIN_PASS=secret

# Base de datos (se usan para autoconfigurar la conexión)
DB_HOST=postgres
DB_NAME=commandaflow_saas
DB_USER=postgres
DB_PASS=secret
```

## 🎯 Flujo de inicio automatizado:

1. **Inicio del contenedor** → Ejecuta `entrypoint.sh`
2. **Generación de pgpass** → Crea archivo con credenciales desde variables de entorno
3. **Configuración de permisos** → Aplica permisos correctos al archivo
4. **Carga de servidores** → Lee `servers.json` con servidor preconfigurado
5. **Login automático** → pgAdmin se conecta automáticamente sin pedir credenciales

## 🔐 Seguridad:

- ✅ Archivo pgpass con permisos 600 (solo owner puede leer)
- ✅ Credenciales desde variables de entorno (no hardcodeadas)
- ✅ Sin contraseña maestra requerida
- ✅ Conexión SSL configurada (prefer mode)

## 🚀 Uso:

1. **Configurar variables** en `.env`
2. **Iniciar contenedores**: `docker-compose up -d`
3. **Abrir navegador**: http://localhost:8082
4. **Login** con email/password de pgAdmin
5. **¡Listo!** El servidor PostgreSQL aparece automáticamente conectado

## 🔧 Personalización:

Para agregar más servidores, edita `servers.json`:

```json
{
    "Servers": {
        "1": { /* Servidor principal */ },
        "2": {
            "Name": "Otro Servidor",
            "Host": "otro-host",
            /* ... más configuración ... */
        }
    }
}
```

## 📋 Troubleshooting:

- **No aparece el servidor**: Verificar logs con `docker-compose logs pgadmin`
- **Error de conexión**: Verificar que PostgreSQL esté ejecutándose
- **Credenciales incorrectas**: Verificar variables de entorno en `.env`
- **Puerto ocupado**: Cambiar `PGADMIN_PORT` en `.env`

## 📊 Ventajas vs configuración manual:

| Aspecto | Manual | Automatizado |
|---------|--------|--------------|
| Setup inicial | 5+ minutos | 0 minutos |
| Configuración servidor | Manual cada vez | Automático |
| Credenciales | Introducir manualmente | Automático |
| Nuevos entornos | Reconfigurar todo | Solo cambiar .env |
| Equipo desarrollo | Cada dev configura | Misma config todos |