# Guía de Contribución al SGC Memoria Castrense

Para garantizar la estabilidad del código en producción y la legibilidad del historial de cambios, todo el equipo debe acatar las siguientes normativas antes de aportar código.

## 1. Protección de la Rama Principal
La rama `main` (o `master`) **está bloqueada**. 
Nadie tiene permitido realizar un `git push` directo a `main`. Todo el código nuevo debe integrarse exclusivamente a través de un **Pull Request (PR)**.

## 2. Flujo de Trabajo (Git Flow)
Se adopta una versión simplificada de Git Flow. Cada tarea debe desarrollarse en una rama independiente, cuyo nombre debe reflejar el propósito de la tarea según la siguiente nomenclatura:

- **Funcionalidades Nuevas:** `feature/nombre-de-la-tarea`
  - *Ejemplo:* `feature/modulo-marcadores`, `feature/login-castrense`
- **Corrección de Errores (Bugs):** `bugfix/nombre-del-error`
  - *Ejemplo:* `bugfix/error-carga-pdf`, `bugfix/conexion-sqlite`
- **Hotfixes (Errores urgentes en producción):** `hotfix/nombre-del-error`
- **Documentación:** `docs/nombre-de-la-documentacion`

## 3. Conventional Commits (Convención de Mensajes)
El historial de cambios debe ser limpio y predecible. Todos los mensajes de commit deben seguir la estructura de **Conventional Commits**:

`<tipo>: <descripción corta>`

### Tipos Permitidos:
- **feat:** Añade una nueva característica o módulo al sistema. *(Ej: `feat: integrar login de administradores`)*.
- **fix:** Soluciona un error en el código. *(Ej: `fix: corregir tamaño máximo permitido de imágenes`)*.
- **docs:** Cambios exclusivos de documentación. *(Ej: `docs: actualizar diagrama entidad relacion`)*.
- **style:** Cambios que no afectan el significado del código (espacios, formateo, punto y coma).
- **refactor:** Cambios en el código que no corrigen errores ni añaden funcionalidades (optimización).
- **test:** Añadir pruebas faltantes o corregir pruebas existentes.
- **chore:** Cambios en el proceso de build, dependencias (ej: composer.json) o herramientas auxiliares.

## 4. Proceso de Integración Continua (CI)
Todo PR activará nuestro robot de **GitHub Actions**. El código no será fusionado si el robot de CI marca el estado en **ROJO** (fallo en dependencias o linter). Si la validación falla, el desarrollador debe revisar los logs de error, corregirlo en su rama, y hacer un nuevo commit.

## 5. Peer Review (Revisión de Pares)
Antes de fusionar (merge) un PR a `main`, se requiere la revisión y aprobación obligatoria de **al menos 1 miembro del equipo**. Esta persona será responsable de verificar la calidad del código entrante.
