# RUNBOOK — SGC Memoria Castrense

Guía de primeros auxilios para el Sistema Gestor de Contenido del Archivo Histórico Militar de La Vela de Coro.

---

## Fase #1: Diagnóstico

### Health Endpoint

```bash
curl https://sgc-memoriacastrense.onrender.com/health
```

Respuesta esperada:

```json
{
  "status": "ok",
  "application": "SGC Memoria Castrense",
  "checks": {
    "database": { "status": "ok", "latency_ms": 5 },
    "storage": { "status": "ok", "writable": true }
  }
}
```

### Verificar logs en Render

1. Ir a [Render Dashboard](https://dashboard.render.com) > sgc-memoriacastrense > **Logs**
2. Buscar errores recientes: filtrar por `ERROR` o `Exception`
3. Los logs estructurados están en `storage/logs/laravel.json` (formato JSON)

### Verificar estado de la aplicación

```bash
# Probar página principal
curl -I https://sgc-memoriacastrense.onrender.com/

# Probar conexión a base de datos (vía Tinker)
php artisan tinker --execute="DB::select('SELECT 1')"
```

### Comandos de diagnóstico local

```bash
# Estado de la aplicación
php artisan about

# Listar rutas registradas
php artisan route:list

# Verificar caché
php artisan cache:status

# Verificar migraciones pendientes
php artisan migrate:status

# Ver logs en tiempo real
tail -f storage/logs/laravel.json
```

---

## Fase #2: Protocolo ante Caídas

### Niveles de escalado

| Nivel | Responsable | Acción |
|-------|-------------|--------|
| **L1** | Operador / Desarrollador | Diagnóstico inicial y contención |
| **L2** | Desarrollador senior | Análisis de causa raíz y parche |
| **L3** | Arquitecto / DevOps | Rediseño de infraestructura si aplica |

### SLA: 99.5% uptime (≈ 3.65h de caída mensual)

### Pasos mecánicos

#### L1 — Contención (0–15 min)

1. **Verificar salud**: `curl https://sgc-memoriacastrense.onrender.com/health`
2. **Revisar logs en Render**: buscar errores 500, timeouts de DB, o pánico de PHP
3. **Verificar PostgreSQL en Render**: ir a Dashboard > PostgreSQL > Logs
4. **Si es error de almacenamiento**: verificar que `storage/` y `bootstrap/cache/` tengan permisos de escritura
5. **Si es 502/503**: reiniciar el servicio desde Render Dashboard > **Manual Deploy > Clear build cache & deploy**
6. **Documentar**: timestamp, síntoma, UUID del error (si aplica)

#### L2 — Análisis (15–60 min)

1. **Reproducir localmente**: `git checkout main && ./vendor/bin/phpunit`
2. **Verificar variables de entorno**: comparar `.env` local con las variables en Render Dashboard > Environment
3. **Revisar errores recientes en logs JSON**:
   ```bash
   grep -i "error" storage/logs/laravel.json | tail -20
   ```
4. **Si es error de migración**: `php artisan migrate:status` en Render vía SSH (o ejecutar migrate:reset local + PR)
5. **Si es error de dependencias**: `composer install --no-dev` local y verificar versión de PHP

#### L3 — Escalado (60+ min)

1. **Si es problema de infraestructura**: evaluar aumentar recursos en Render (plan upgrade)
2. **Si es corrupción de datos**: proceder a Fase #3 (Recuperación ante Desastres)
3. **Si es vulnerabilidad de seguridad**: cerrar endpoint, notificar al equipo, parchear

---

## Fase #3: Recuperación ante Desastres

### Regla 3-2-1

| Concepto | Implementación |
|----------|----------------|
| **3 copias** | Producción (Render) + Backup diario + Copia local del desarrollador |
| **2 medios** | PostgreSQL (DB) + Almacenamiento de archivos (S3/Cloudinary pendiente) |
| **1 copia off-site** | Backup de PostgreSQL exportado a almacenamiento externo |

### Backup de PostgreSQL

```bash
# Exportar (desde máquina con acceso a Render)
pg_dump --host=<DB_HOST> --port=<DB_PORT> --username=<DB_USER> --dbname=<DB_DATABASE> \
  --format=custom --file=backup_$(date +%Y%m%d).dump

# Comprimir
gzip backup_*.dump
```

### Restauración desde cero

```bash
# 1. Clonar repositorio
git clone https://github.com/TheLastSh/SGC-MemoriaCastrense.git
cd SGC-MemoriaCastrense

# 2. Configurar entorno
cp .env.example .env
# Editar .env con credenciales de producción (DB, APP_KEY, etc.)

# 3. Construir y desplegar (Docker)
docker build -t sgc-memoria-castrense .
docker run -d --name sgc -p 8000:8000 --env-file .env sgc-memoria-castrense

# 4. Restaurar base de datos
pg_restore --host=<DB_HOST> --port=<DB_PORT> --username=<DB_USER> \
  --dbname=<DB_DATABASE> --clean --if-exists backup_20260719.dump

# 5. Ejecutar migraciones pendientes
php artisan migrate --force

# 6. Regenerar caché
php artisan view:cache
php artisan event:cache

# 7. Verificar salud
curl http://localhost:8000/health

# 8. Limpiar archivos temporales
php artisan storage:link
```

### En Render (despliegue automático)

```bash
# 1. Hacer push a main
git push origin main

# 2. Render ejecuta el build y deploy automáticamente
#    (vía Deploy Hook configurado en GitHub Actions)

# 3. Verificar deploy en: Render Dashboard > Deploys
```

### Checklist post-mortem

- [ ] ¿Se identificó la causa raíz?
- [ ] ¿Se aplicó un parche permanente? (no solo mitigación temporal)
- [ ] ¿Se actualizó este RUNBOOK con la lección aprendida?
- [ ] ¿Se notificó a los usuarios afectados?
- [ ] ¿Se restauraron los backups y verificaron los datos?
- [ ] ¿Se ejecutaron los tests (`vendor/bin/phpunit`) tras la recuperación?
