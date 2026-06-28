# Contexto y Análisis Exhaustivo para Modelos de IA (SGC Memoria Castrense)

Este documento sirve como contexto provisional para cualquier modelo de Inteligencia Artificial que vaya a asistir en el desarrollo, mantenimiento o refactorización del proyecto **SGC Memoria Castrense**. Contiene un análisis exhaustivo del estado actual del proyecto, tecnologías, fallas detectadas y el roadmap de mejoras a implementar.

---

## 1. Stack Tecnológico y Arquitectura

El proyecto es un **Monolito** que prioriza el SEO y la baja latencia, evitando la separación entre frontend y backend en servidores distintos.

- **Lenguaje Core:** PHP 8.1 / 8.2.
- **Framework:** Laravel 10.
- **Frontend:** Laravel Blade, Tailwind CSS (vía Vite), Vanilla JavaScript. No se usa ningún framework reactivo (Vue/React).
- **Base de Datos:**
  - **Desarrollo (Local):** SQLite (configurado por defecto en `.env.example`).
  - **Producción:** PostgreSQL.
- **ORM y Arquitectura:** Eloquent ORM. Arquitectura MVC complementada con el **Patrón de Servicios** (`RegistroPatrimonialService`) para encapsular lógica de negocio y transacciones (ACID).
- **Almacenamiento de Archivos:** Integración con servicios en la nube (S3/Cloudinary) debido a la naturaleza efímera del PaaS (Render). La DB almacena `url_recurso` en lugar de binarios o rutas locales.

---

## 2. Estado de la Documentación

El proyecto cuenta con una documentación sobresaliente (Doc-as-Code). Los siguientes archivos deben ser revisados para entender a fondo el negocio:

- `AGENTS.md`: Reglas del sistema, comandos rápidos, credenciales y convenciones. (Punto de entrada principal para IA).
- `README.md`: Arquitectura de despliegue, Diagrama ER y flujos de secuencia en Mermaid.
- `contexto_sgc.txt`: Resumen rápido del caso de negocio y stack.
- `docs/documentacion_tecnica.md`: Requerimientos funcionales, historias de usuario y matriz de tareas.
- `CONTRIBUTING.md`: Convenciones de Git y estilo de código.

**Veredicto:** La documentación está altamente madura y al día.

---

## 3. Análisis de Fallas y Deuda Técnica (Bugs)

Durante el análisis del código fuente y configuraciones, se detectaron las siguientes fallas que deben ser corregidas:

### 3.1. Incompatibilidad de Base de Datos en Búsquedas (`ilike`)
- **Ubicación:** `app/Http/Controllers/RegistroPatrimonialController.php@index` (Líneas 24-25).
- **Problema:** Se está utilizando el operador `ilike` para las búsquedas. Este operador es **exclusivo de PostgreSQL**. Puesto que el entorno local y de pruebas (`phpunit.xml`) usa **SQLite**, cualquier intento de búsqueda en desarrollo arrojará un error SQL (`General error: 1 near "ilike": syntax error`).
- **Impacto:** Rompe la paridad de entornos y el flujo de desarrollo local.

### 3.2. Carencia de Pruebas Automatizadas
- **Ubicación:** Directorio `tests/Feature/`.
- **Problema:** A pesar de que `phpunit.xml` está configurado para pruebas en memoria, actualmente solo existe el `ExampleTest.php` genérico de Laravel.
- **Impacto:** Las transacciones críticas, como el "Rollback Físico" en S3 gestionado por el `RegistroPatrimonialService`, no tienen cobertura de pruebas, aumentando el riesgo de regresiones.

### 3.3. Ausencia de Pipelines de Integración Continua (CI)
- **Problema:** Como se menciona en `AGENTS.md`, no existen flujos de GitHub Actions u otro sistema CI/CD.
- **Impacto:** No hay validación automática de estilo de código (Laravel Pint) ni ejecución de pruebas antes de fusionar Pull Requests.

### 3.4. Discrepancias Menores en Documentación
- `composer.json` exige PHP `^8.1`, pero `contexto_sgc.txt` documenta PHP 8.2 como lenguaje core.

---

## 4. Mejoras a Implementar (Roadmap de Cambios)

Basado en el análisis anterior, los siguientes cambios deben ser aplicados por el desarrollador (o la IA asistente) en las próximas iteraciones:

### [MEJORA 1] Refactorización de Búsqueda Agnóstica (Cross-DB)
Reemplazar el uso duro de `ilike` en `RegistroPatrimonialController` por una solución que funcione tanto en SQLite como en PostgreSQL.
**Solución propuesta:**
```php
// Opción A: Usar LOWER() para emular case-insensitivity
$termino = strtolower('%' . $request->buscar . '%');
$query->whereRaw('LOWER(titulo) LIKE ?', [$termino])
      ->orWhereRaw('LOWER(descripcion) LIKE ?', [$termino]);

// Opción B: Usar where() normal con 'like', ya que SQLite 'like' es case-insensitive por defecto, 
// y en PostgreSQL dependería de la collation o forzar ILIKE dinámicamente según el driver.
```

### [MEJORA 2] Implementación de Tests de Integración
- Crear un `RegistroPatrimonialTest.php` en `tests/Feature/`.
- **Casos a cubrir:**
  1. Creación exitosa de un registro y validación de inserción en DB.
  2. Fallo simulado en la transacción de DB para validar que el servicio elimina el archivo de Storage (Rollback físico).
  3. Búsqueda de registros para validar la corrección de la [MEJORA 1].

### [MEJORA 3] Configuración de CI/CD (GitHub Actions)
- Crear archivo `.github/workflows/tests.yml`.
- **Jobs requeridos:**
  - Instalación de dependencias de Composer y NPM.
  - Ejecución de `vendor/bin/pint --test` para validación de convenciones.
  - Ejecución de `php artisan test` con base de datos SQLite en memoria.

---

> **Instrucción para IA:** Antes de proponer o ejecutar código en este proyecto, lee detenidamente los archivos mencionados en la sección 2 y asegúrate de que tus modificaciones abordan las fallas descritas en la sección 3.
