# Sistema Gestor de Contenido (SGC) - Memoria Castrense

El **SGC Memoria Castrense** es una plataforma web institucional orientada a la preservación, catalogación y discusión colaborativa de documentos históricos militares.

El proyecto garantiza la **Paridad de Entornos**, utiliza **Arquitectura Monolítica** en Laravel (PHP) con motor **PostgreSQL/SQLite** e implementa **Integración Continua (CI/CD)**. Todo el desarrollo se rige bajo estrictas políticas de [CONTRIBUTING.md](CONTRIBUTING.md).

> **Nota sobre Gestión de Proyecto:** El Plan de Acción detallado (Diagrama de Gantt y Sprints) se gestiona de forma activa en nuestro [Tablero de Notion](https://app.notion.com/p/Sistema-Gestor-de-Contenido-3922c23f08af80428d92d02acb104d20?source=copy_link).

---

## 🏗️ Arquitectura como Código (Doc-as-Code)

### 1. Diagrama de Arquitectura de Despliegue

**Justificación de la Arquitectura Monolítica vs SPA/API:** 
Se migró de una arquitectura distribuida (Vercel + Render) a un monolito puro (Render) tras evaluar la viabilidad técnica y los costos de infraestructura. El monolito reduce la latencia de red a 0ms entre el frontend (Blade) y el backend, simplifica el pipeline de CI/CD, y optimiza radicalmente el SEO (Crucial para que los artículos históricos sean indexados por motores de búsqueda), todo esto utilizando un solo servidor (Reduciendo costos en un 50%).

**Gestión de Archivos Efímeros:**
Dado que plataformas PaaS como Render utilizan sistemas de archivos efímeros que se borran con cada despliegue, la arquitectura integra un **Almacenamiento Externo de Objetos (Cloudinary / Amazon S3)**. De esta forma, las imágenes y PDFs persisten de forma segura en un Bucket en la nube, justificando que la base de datos guarde la URL completa del recurso (`filename`) en lugar de un path local.

```mermaid
flowchart TD
    Cliente["Navegador del Historiador"]
    
    subgraph Servidor_Nube ["Servidor Monolítico en la Nube (Render/PaaS)"]
        Laravel["Aplicación Laravel (Backend + Frontend)"]
        PostgreSQL[("Base de Datos SQL")]
    end
    
    subgraph Storage_Externo ["Object Storage"]
        S3[("Amazon S3 / Cloudinary<br>(Archivos Persistentes)")]
    end
    
    Cliente -->|"Tráfico HTTPS"| Laravel
    Laravel -->|"Vistas HTML / CSS"| Cliente
    Laravel -->|"Consultas ORM"| PostgreSQL
    Laravel -->|"API I/O Archivos"| S3
```

---

### 2. Diagrama Entidad-Relación (ER)

El modelo actual refleja la arquitectura de contenido histórico, foro de discusión y verificación de usuarios.

```mermaid
erDiagram
    USERS ||--o{ ARTICULOS : "escribe (author_id)"
    USERS ||--o{ COMENTARIOS : "comenta"
    USERS ||--o{ HILOS : "crea (autor_id)"
    USERS ||--o{ RESPUESTAS : "responde"
    USERS ||--o{ SOLICITUDES_VERIFICACION : "solicita"
    USERS ||--o{ MEDIA : "sube"
    USERS }|--|{ ARTICULOS : "favoritos"
    CATEGORIAS ||--o{ ARTICULOS : "clasifica"
    ARTICULOS ||--o{ COMENTARIOS : "recibe"
    ARTICULOS }|--|{ TAGS : "articulo_tag"
    FORO_CATEGORIAS ||--o{ HILOS : "contiene"
    HILOS ||--o{ RESPUESTAS : "tiene"

    USERS {
        BIGINT id PK
        VARCHAR name
        VARCHAR email
        VARCHAR password
        VARCHAR role
        VARCHAR tipo_verificado
        TEXT biografia
        TIMESTAMP deleted_at
    }

    ARTICULOS {
        BIGINT id PK
        VARCHAR titulo
        VARCHAR slug
        TEXT extracto
        TEXT contenido
        VARCHAR portada_url
        VARCHAR status
        BIGINT author_id FK
        BIGINT categoria_id FK
        DATE fecha_publicacion
        INT visitas
        TIMESTAMP deleted_at
    }

    CATEGORIAS {
        BIGINT id PK
        VARCHAR nombre
        TEXT descripcion
    }

    TAGS {
        BIGINT id PK
        VARCHAR nombre
        VARCHAR slug
    }

    COMENTARIOS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT articulo_id FK
        TEXT contenido
    }

    MEDIA {
        BIGINT id PK
        BIGINT subido_por FK
        VARCHAR nombre_original
        TEXT filename
        VARCHAR mime_type
        FLOAT peso_kb
        INT ancho
        INT alto
        VARCHAR alt_text
        TEXT descripcion
        VARCHAR coleccion
    }

    FORO_CATEGORIAS {
        BIGINT id PK
        VARCHAR nombre
        VARCHAR slug
        TEXT descripcion
        INT orden
    }

    HILOS {
        BIGINT id PK
        VARCHAR titulo
        VARCHAR slug
        TEXT contenido_inicial
        BIGINT autor_id FK
        BIGINT categoria_id FK
        VARCHAR status
        BOOLEAN fijado
        BIGINT ultima_respuesta_id FK
        TIMESTAMP deleted_at
    }

    RESPUESTAS {
        BIGINT id PK
        BIGINT hilo_id FK
        BIGINT autor_id FK
        TEXT contenido
        BOOLEAN editado
        TIMESTAMP deleted_at
    }

    SOLICITUDES_VERIFICACION {
        BIGINT id PK
        BIGINT user_id FK
        VARCHAR tipo
        VARCHAR documento_path
        TEXT resena_curricular
        VARCHAR status
        BIGINT revisado_por FK
        TEXT motivo_rechazo
        DATETIME fecha_verificacion
    }
```

---

### 3. Diagrama de Secuencia (Publicación de Artículos con Manejo de Archivos Huérfanos)

**Lógica de Negocio y Transacciones:**
Para evitar acoplamiento, la lógica de negocio reside en la capa `ArticuloService`. El registro en base de datos está envuelto en un bloque `DB::transaction`. Si la base de datos rechaza la inserción, el sistema captura la excepción (`catch`) y **borra el archivo del disco** automáticamente.

```mermaid
sequenceDiagram
    actor Pub as Publicador
    participant GUI as Interfaz Web
    participant Ctrl as ArticuloController
    participant Service as ArticuloService
    participant Disk as Storage (Local/Nube)
    participant DB as Motor SQL

    Pub->>GUI: Llena formulario y sube portada
    GUI->>Ctrl: POST /archivo/crear
    Ctrl->>Ctrl: Validación de request
    
    alt Datos inválidos
        Ctrl-->>GUI: Redirigir con errores
        GUI-->>Pub: Muestra errores de validación
    else Datos válidos
        Ctrl->>Service: publicarArticulo(datos, tags, portada, userId)
        
        alt Hay portada
            Service->>Disk: store(portada)
            Disk-->>Service: Retorna URL
        end
        
        Service->>DB: INICIAR TRANSACCIÓN
        Service->>DB: INSERT articulo + sync tags
        
        alt Fallo SQL
            DB-->>Service: Lanza Exception
            Service->>Disk: Eliminar portada (Rollback Físico)
            Service->>DB: ROLLBACK
            Service-->>Ctrl: Lanza Exception
            Ctrl-->>GUI: Error interno
            GUI-->>Pub: Alerta de error
        else Éxito
            DB-->>Service: COMMIT
            Service-->>Ctrl: Articulo creado
            Ctrl-->>GUI: Redirigir a catálogo
            GUI-->>Pub: Mensaje de éxito
        end
    end
```

---

### 4. Casos de Uso del Sistema

```mermaid
flowchart LR
    Guest(["Visitante"])
    User(["Usuario Registrado"])
    Pub(["Publicador"])
    Admin(["Administrador"])

    subgraph SGC ["Sistema Gestor de Contenido"]
        UC1("Navegar Artículos")
        UC2("Buscar en el Archivo")
        UC3("Ver Foro")
        UC4("Iniciar Sesión / Registrarse")
        UC5("Comentar Artículos")
        UC6("Gestionar Favoritos")
        UC7("Crear Hilos en el Foro")
        UC8("Responder Hilos")
        UC9("Solicitar Verificación")
        UC10("Crear / Editar Artículos")
        UC11("Subir Archivos a Biblioteca")
        UC12("Archivar Artículos (Soft Delete)")
        UC13("Aprobar / Rechazar Verificaciones")
        UC14("Eliminar Comentarios y Respuestas")
    end

    Guest --> UC1
    Guest --> UC2
    Guest --> UC3
    Guest --> UC4

    User --> UC4
    User --> UC1
    User --> UC5
    User --> UC6
    User --> UC7
    User --> UC8
    User --> UC9

    Pub --> UC4
    Pub --> UC10
    Pub --> UC11

    Admin --> UC4
    Admin --> UC10
    Admin --> UC12
    Admin --> UC13
    Admin --> UC14
```

---

## 📖 Documentación Técnica (Doc-as-Code)

La documentación de clases (PHPDoc) se genera automáticamente con **phpDocumentor**:

```bash
# Generar documentación (salida en public/docs/)
composer docs

# Servir la documentación localmente
php -S localhost:8000 -t public/docs
```

Todas las clases en `app/Http/Controllers/`, `app/Models/` y `app/Services/` incluyen PHPDoc completo.

> Proyecto académico desarrollado para la materia Implantación de Sistemas (2026). Equipo: Eduardo Rojas & Ernesto Polanco.