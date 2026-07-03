# Sistema Gestor de Contenido (SGC) - Memoria Castrense

El **SGC Memoria Castrense** es una plataforma web institucional orientada a la preservación, catalogación y discusión colaborativa de documentos históricos militares.

El proyecto garantiza la **Paridad de Entornos**, utiliza **Arquitectura Monolítica** en Laravel (PHP) con motor **PostgreSQL/SQLite** e implementa **Integración Continua (CI/CD)**. Todo el desarrollo se rige bajo estrictas políticas de [CONTRIBUTING.md](CONTRIBUTING.md).

> **Nota sobre Gestión de Proyecto:** El Plan de Acción detallado (Diagrama de Gantt y Sprints) se gestiona de forma activa en nuestro entorno de **Notion / ClickUp** [Enlace al Tablero de Gestión].

---

## 🏗️ Arquitectura como Código (Doc-as-Code)

### 1. Diagrama de Arquitectura de Despliegue

**Justificación de la Arquitectura Monolítica vs SPA/API:** 
Se migró de una arquitectura distribuida (Vercel + Render) a un monolito puro (Render) tras evaluar la viabilidad técnica y los costos de infraestructura. El monolito reduce la latencia de red a 0ms entre el frontend (Blade) y el backend, simplifica el pipeline de CI/CD, y optimiza radicalmente el SEO (Crucial para que los artículos históricos sean indexados por motores de búsqueda), todo esto utilizando un solo servidor (Reduciendo costos en un 50%).

**Gestión de Archivos Efímeros:**
Dado que plataformas PaaS como Render utilizan sistemas de archivos efímeros que se borran con cada despliegue, la arquitectura integra un **Almacenamiento Externo de Objetos (Cloudinary / Amazon S3)**. De esta forma, las imágenes y PDFs persisten de forma segura en un Bucket en la nube, justificando que la base de datos guarde la URL completa del recurso (`url_recurso`) en lugar de un path local.

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

Se corrigió la entidad `COMENTARIOS` añadiendo sus llaves primarias, foráneas y timestamps necesarios para su funcionamiento en producción.

```mermaid
erDiagram
    USERS ||--o{ REGISTROS_PATRIMONIALES : "crea (created_by)"
    CATEGORIAS ||--o{ REGISTROS_PATRIMONIALES : "clasifica"
    REGISTROS_PATRIMONIALES ||--o{ ARCHIVOS : "posee (1:N)"
    USERS ||--o{ COMENTARIOS : "escribe"
    REGISTROS_PATRIMONIALES ||--o{ COMENTARIOS : "recibe"
    USERS }|--|{ REGISTROS_PATRIMONIALES : "guarda_marcador"

    USERS {
        BIGINT id PK
        VARCHAR name
        VARCHAR email
        VARCHAR password
        VARCHAR role
    }
    
    CATEGORIAS {
        BIGINT id PK
        VARCHAR nombre
        TEXT descripcion
    }

    REGISTROS_PATRIMONIALES {
        UUID id PK
        VARCHAR titulo
        TEXT descripcion
        DATE fecha_suceso
        BIGINT id_categoria FK
        BIGINT created_by FK
        TIMESTAMP deleted_at
    }

    ARCHIVOS {
        BIGINT id PK
        UUID registro_id FK
        TEXT url_recurso
        VARCHAR nombre_original
        VARCHAR tipo_archivo
        INT peso_archivo_kb
    }
    
    COMENTARIOS {
        BIGINT id PK
        BIGINT user_id FK
        UUID registro_id FK
        TEXT contenido
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
```

---

### 3. Diagrama de Secuencia (Manejo de Transacciones y Archivos Huérfanos)

**Lógica de Negocio y Transacciones:**
Para evitar acoplamiento, la lógica de negocio no reside en el Controlador, sino en la capa `RegistroPatrimonialService`. Adicionalmente, el registro en base de datos está envuelto en un bloque `DB::transaction`. Si la base de datos rechaza la inserción (Ej. Constraints, Timeout), el sistema captura la excepción (`catch`) y **borra el archivo del disco de la nube** automáticamente, evitando la acumulación de archivos huérfanos.

```mermaid
sequenceDiagram
    actor Admin as Administrador
    participant GUI as Interfaz Web
    participant Ctrl as Controlador
    participant Service as RegistroService
    participant S3 as Storage (Nube)
    participant DB as Motor SQL

    Admin->>GUI: Sube formulario y PDF
    GUI->>Ctrl: POST /ingesta
    Ctrl->>Ctrl: Validación de request
    
    alt Datos inválidos
        Ctrl-->>GUI: Redirigir con errores
        GUI-->>Admin: Muestra alertas rojas
    else Datos válidos
        Ctrl->>Service: preservarDocumento(datos, archivo)
        Service->>S3: Guardar archivo fisico
        S3-->>Service: Retorna URL segura
        
        Service->>DB: INICIAR TRANSACCIÓN SQL
        Service->>DB: Insertar Registro y Archivo
        
        alt Fallo SQL (Rechazo o Timeout)
            DB-->>Service: Lanza PDOException
            Service->>S3: Eliminar archivo huérfano (Rollback Físico)
            Service->>DB: ABORTAR TRANSACCIÓN (Rollback SQL)
            Service-->>Ctrl: Lanza Exception
            Ctrl-->>GUI: Retorna Error al Guardar
            GUI-->>Admin: Muestra alerta de error
        else Inserción SQL Exitosa
            DB-->>Service: COMMIT TRANSACCIÓN
            Service-->>Ctrl: Retorna Instancia Guardada
            Ctrl-->>GUI: Redirigir a Catálogo
            GUI-->>Admin: Mensaje de Exito
        end
    end
```

---

### 4. Casos de Uso del Sistema

```mermaid
flowchart LR
    Guest(["Visitante Anónimo"])
    User(["Usuario Registrado"])
    Pub(["Publicador / Archivista"])
    Admin(["Administrador"])

    subgraph SGC ["Sistema Gestor de Contenido"]
        UC1("Consultar Catálogo Público")
        UC2("Ver Efemérides Históricas")
        UC3("Iniciar Sesión")
        UC4("Comentar Registros")
        UC5("Gestionar Favoritos")
        UC6("Subir Nuevos Documentos")
        UC7("Archivar Documentos (Soft Delete)")
        UC8("Moderar Comentarios")
    end

    Guest --> UC1
    Guest --> UC2

    User --> UC3
    User --> UC1
    User --> UC4
    User --> UC5

    Pub --> UC3
    Pub --> UC1
    Pub --> UC6

    Admin --> UC3
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
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