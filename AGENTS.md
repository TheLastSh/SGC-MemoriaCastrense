# AGENTS.md — Archivo Histórico Militar de La Vela de Coro

Laravel 10 monolith (PHP 8.2+) para la preservación y divulgación de la historia militar de La Vela de Coro, estado Falcón, Venezuela. Blade + Tailwind CSS 3, Trix editor, SQLite/PostgreSQL.

## Quick start

```bash
cp .env.example .env            # DB_CONNECTION=sqlite (default)
composer install
npm install && npm run build    # Vite → public/build/
php artisan key:generate        # ya hecho
php artisan storage:link        # public/storage → storage/app/public
php artisan migrate:fresh --seed
php artisan serve               # http://localhost:8000
```

## Seed credentials

| Role | Email | Password |
|------|-------|----------|
| administrador | admin@memoriacastrense.gob.ve | Password123 |
| publicador | publicador@memoriacastrense.gob.ve | Password123 |
| usuario | usuario@memoriacastrense.gob.ve | Password123 |

## Commands

| Action | Command |
|--------|---------|
| Dev server | `npm run dev` (Vite) + `php artisan serve` |
| Build assets | `npm run build` |
| Tests | `vendor/bin/phpunit` or `php artisan test` |
| Single test | `vendor/bin/phpunit tests/Feature/ArticuloTest.php` |
| DB reset + seed | `php artisan migrate:fresh --seed` |
| Code style | `vendor/bin/pint` |
| Tinker | `php artisan tinker` |

## Routes (`routes/web.php`)

### Públicas
| Método | URI | Nombre | Controlador |
|--------|-----|--------|-------------|
| GET | `/` | `home` | HomeController@index |
| GET | `/archivo` | `articulos.index` | ArticuloController@index |
| GET | `/archivo/{articulo}` | `articulos.show` | ArticuloController@show |
| GET | `/biblioteca` | `media.index` | MediaController@index |
| GET | `/biblioteca/{media}` | `media.show` | MediaController@show |
| GET | `/foro` | `foro.index` | ForoController@index |
| GET | `/foro/{categoria}` | `foro.categoria` | ForoController@showCategoria |
| GET | `/foro/hilo/{hilo}` | `foro.hilo` | ForoController@showHilo |

### Autenticadas (guest)
| GET | `/login` | `login` | AuthController@showLogin |
| POST | `/login` | — | AuthController@login |
| GET | `/registro` | `register` | AuthController@showRegister |
| POST | `/registro` | — | AuthController@register |

### Autenticadas (auth)
| POST | `/logout` | `logout` | AuthController@logout |
| POST | `/archivo/{articulo}/comentarios` | `comentarios.store` | ComentarioController@store |
| DELETE | `/comentarios/{comentario}` | `comentarios.destroy` | ComentarioController@destroy |
| GET/POST | `/foro/{categoria}/nuevo-hilo` | `foro.create-hilo` / `foro.store-hilo` | ForoController |
| POST | `/foro/hilo/{hilo}/responder` | `foro.responder` | ForoController@storeRespuesta |
| DELETE | `/foro/respuesta/{respuesta}` | `foro.respuesta.destroy` | ForoController |
| GET/POST | `/verificacion/solicitar` | `verificacion.solicitar` / `verificacion.store` | VerificacionController |

### Role: administrador,publicador
| GET/POST | `/biblioteca/subir` | `media.create` / `media.store` | MediaController |
| GET/POST | `/archivo/crear` | `articulos.create` / `articulos.store` | ArticuloController |
| GET | `/archivo/{articulo}/editar` | `articulos.edit` | ArticuloController@edit |
| PUT/DELETE | `/archivo/{articulo}` | `articulos.update` / `articulos.destroy` | ArticuloController |

### Role: administrador
| GET | `/verificaciones/pendientes` | `verificacion.pendientes` | VerificacionController |
| POST | `/verificaciones/{solicitud}/aprobar` | `verificacion.aprobar` | VerificacionController |
| POST | `/verificaciones/{solicitud}/rechazar` | `verificacion.rechazar` | VerificacionController |
| DELETE | `/biblioteca/{media}` | `media.destroy` | MediaController |

## Models & relationships

```
User (role, tipo_verificado, biografia)
 ├── author_id ── Articulo (HasMany)
 ├── autor_id ── Hilo (HasMany)
 ├── autor_id ── Respuesta (HasMany)
 ├── user_id ── Comentario (HasMany)
 └── user_id ── SolicitudVerificacion (HasOne)

Articulo (SoftDeletes, slug)
 ├── belongsTo: User (autor), Categoria (categoria_id)
 ├── belongsToMany: Tag (pivot articulo_tag)
 └── hasMany: Comentario (articulo_id)

Categoria (categorias) ── hasMany: Articulo

Tag ── belongsToMany: Articulo

Media (subido_por → User)

ForoCategoria (foro_categorias) ── hasMany: Hilo

Hilo (SoftDeletes, slug, status: abierto|cerrado, fijado)
 ├── belongsTo: User (autor), ForoCategoria (categoria_id)
 └── hasMany: Respuesta

Respuesta ── belongsTo: Hilo, User (autor_id)

SolicitudVerificacion (status: pendiente|aprobado|rechazado)
 └── belongsTo: User (user_id), User (revisado_por)

Comentario (articulo_id, user_id)
 └── belongsTo: Articulo, User
```

## Architecture

- **Auth**: `AuthController` (login/register/logout) — no Breeze/Jetstream
- **RBAC**: `role` column (`administrador`, `publicador`, `usuario`). Middleware `EnsureUserHasRole` con alias `role:`
- **Verificación**: `tipo_verificado` en users (`historiador`, `cultor`, `cronista`) — lo asigna admin vía `SolicitudVerificacion`
- **WYSIWYG**: Trix editor vía CDN (`unpkg.com/trix@2.0.8`)
- **File uploads**: local en `storage/app/public/` (vía `Storage::disk('public')`); `php artisan storage:link` requerido
- **Testing DB**: SQLite in-memory en `phpunit.xml` (ya activo)
- **No form requests**: validación inline en controladores
- **Fonts**: Inter + Merriweather via Google Fonts
- **Frontend**: Tailwind CSS 3, sin framework JS

## Tests (12 tests, 22 assertions)

| File | Coverage |
|------|----------|
| `tests/Feature/ExampleTest.php` | Smoke test: home page returns 200 |
| `tests/Feature/ArticuloTest.php` | CRUD artículos, permisos, validación, soft-delete |
| `tests/Feature/ForoTest.php` | Listar categorías, crear hilos, permisos |

Ejecutar: `vendor/bin/phpunit`

## Seed data

- 3 usuarios (admin, publicador, usuario)
- 4 categorías de artículos
- 8 tags
- 2 foro categorías (Documentos Históricos, Hechos Históricos)
- 2 hilos de ejemplo + 1 respuesta
- 2 artículos de ejemplo publicados + tags asignados

## CI/CD

- `.github/workflows/tests.yml`: PHP 8.2, Pint + PHPUnit, SQLite in-memory

## Notas

- Las rutas con parámetros (`{articulo}`, `{media}`, etc.) van DESPUÉS de las rutas fijas para evitar conflictos
- `php artisan route:list` para ver todas las rutas registradas
- `composer.json` incluye: `laravel/sanctum`, `league/flysystem-aws-s3-v3`, `laravel/pint`, `laravel/sail`
- `.env` → `DB_CONNECTION=sqlite` con `database/database.sqlite`
- EditorConfig: 4-space indent, LF, UTF-8
