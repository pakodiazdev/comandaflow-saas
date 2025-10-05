# Cambios en la Gestión de Certificados SSL

## 📋 Resumen de Cambios

Se ha modificado la estrategia de gestión de certificados SSL para mejorar el rendimiento de Docker y facilitar la instalación en Windows.

## 🔄 Cambios Realizados

### 1. **docker-compose.yml**
- ✅ Volumen `docker/cert` ahora solo almacena el Root CA persistente
- ✅ Nuevo volumen `nginx_certs` para certificados generados (temporales, internos)
- ✅ Separación clara: persistencia vs generación

**Antes:**
```yaml
volumes:
  - ./docker/cert:/etc/nginx/certs
```

**Después:**
```yaml
volumes:
  - ./docker/cert:/app/docker/cert  # Solo rootCA.crt (persistente)
  - nginx_certs:/cert  # Certificados generados (temporales)
```

### 2. **docker/nginx/init/init-ssl.sh**
- ✅ Busca `rootCA.crt` en `docker/cert/` (persistente)
- ✅ Si existe, lo reutiliza; si no, lo crea
- ✅ Certificados del dominio se generan en `/cert` (interno)
- ✅ Usa extensión `.crt` para facilitar instalación en Windows (doble click)
- ✅ Mensajes mejorados con instrucciones detalladas por SO

**Flujo:**
1. **Primera ejecución**: Crea `rootCA.crt` → Lo guarda en `docker/cert/` → Genera certificados de dominio
2. **Ejecuciones posteriores**: Carga `rootCA.crt` existente → Regenera certificados de dominio

### 3. **docker/nginx/conf.d/default.conf**
- ✅ Todas las rutas SSL cambiadas de `/etc/nginx/certs` a `/cert`
- ✅ 10 líneas actualizadas en 5 bloques server diferentes

### 4. **docker/cert/.gitignore**
- ✅ Actualizado para permitir trackear `rootCA.crt` y `rootCA-key.pem`
- ✅ Excluye archivos temporales generados

### 5. **docker/cert/README.md**
- ✅ Guía completa de instalación del Root CA
- ✅ Instrucciones específicas para Windows (doble click), macOS y Linux
- ✅ Sección de troubleshooting
- ✅ Explicación del flujo de certificados

## 🎯 Beneficios

### 🚀 Rendimiento
- **Antes**: Certificados en volumen montado (I/O más lento en Windows/macOS)
- **Después**: Certificados en volumen interno (I/O nativo de Docker, mucho más rápido)

### 🪟 Facilidad de Instalación
- **Antes**: Archivo `.pem` (requiere proceso manual en Windows)
- **Después**: Archivo `.crt` (doble click en Windows, se abre automáticamente)

### 🔄 Gestión de Certificados
- **Antes**: Todos los archivos mezclados
- **Después**: 
  - `docker/cert/`: Solo Root CA (persistente, se trackea en Git)
  - `/cert` (interno): Certificados de dominio (temporales, se regeneran)

### 🔒 Seguridad
- Root CA persiste entre reinicios (no se regenera innecesariamente)
- Certificados de dominio se regeneran en cada inicio (siempre frescos)
- Separación clara entre CA (crítico) y certificados (regenerables)

## 📝 Uso

### Primera vez (sin Root CA existente)

```bash
# 1. Iniciar contenedores
docker-compose up -d

# 2. El script creará rootCA.crt en docker/cert/

# 3. Instalar rootCA.crt en tu sistema
# Windows: doble click en docker/cert/rootCA.crt
# macOS: doble click y seguir instrucciones
# Linux: sudo cp docker/cert/rootCA.crt /usr/local/share/ca-certificates/ && sudo update-ca-certificates

# 4. Reiniciar navegador

# 5. Visitar https://comandaflow.local:8443
```

### Reinicios posteriores

```bash
# El Root CA ya está instalado, solo reiniciar:
docker-compose restart web

# Los certificados de dominio se regeneran automáticamente
# No es necesario reinstalar el Root CA
```

### Empezar de cero

```bash
# 1. Detener contenedores
docker-compose down

# 2. Eliminar Root CA
rm docker/cert/rootCA.crt docker/cert/rootCA-key.pem

# 3. Desinstalar Root CA del sistema (ver README.md)

# 4. Iniciar contenedores (creará nuevo Root CA)
docker-compose up -d

# 5. Instalar el nuevo Root CA
```

## 🆘 Troubleshooting

Ver el archivo `docker/cert/README.md` para guía detallada de:
- Instalación por sistema operativo
- Verificación de instalación
- Solución de problemas comunes
- Desinstalación del Root CA

## 📂 Estructura de Archivos

```
docker/cert/
├── .gitignore          # Permite trackear rootCA.crt
├── README.md           # Guía completa de instalación
├── CHANGELOG.md        # Este archivo
├── rootCA.crt          # Root CA (persistente, se crea una vez)
└── rootCA-key.pem      # Private key del Root CA (persistente)

# Los certificados del dominio YA NO están aquí
# Ahora se generan en el volumen interno /cert:
# - /cert/comandaflow.local.pem
# - /cert/comandaflow.local-key.pem
```

## ✅ Checklist de Verificación

- [x] `docker-compose.yml` actualizado con nuevo volumen
- [x] `init-ssl.sh` usa `rootCA.crt` y genera en `/cert`
- [x] `default.conf` usa rutas `/cert`
- [x] `.gitignore` permite trackear Root CA
- [x] `README.md` con instrucciones completas
- [x] Certificados antiguos eliminados de `docker/cert/`

## 📅 Fecha de Cambio

**2025-10-05** - Implementación de la nueva estrategia de certificados

---

**Nota**: Este cambio es compatible con todos los sistemas operativos y no requiere cambios en el código de la aplicación.
