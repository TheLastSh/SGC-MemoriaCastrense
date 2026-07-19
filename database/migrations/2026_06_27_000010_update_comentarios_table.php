<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('comentarios_temp');

        DB::statement('
            CREATE TABLE comentarios_temp (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                articulo_id BIGINT NOT NULL,
                contenido TEXT NOT NULL,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
            )
        ');

        DB::statement('INSERT INTO comentarios_temp (id, user_id, articulo_id, contenido, created_at, updated_at) SELECT id, user_id, registro_id, contenido, created_at, updated_at FROM comentarios');

        Schema::dropIfExists('comentarios');

        DB::statement('ALTER TABLE comentarios_temp RENAME TO comentarios');
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios_temp');

        DB::statement('
            CREATE TABLE comentarios_temp (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                registro_id UUID NOT NULL,
                contenido TEXT NOT NULL,
                created_at TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ');

        DB::statement('INSERT INTO comentarios_temp (id, user_id, registro_id, contenido, created_at, updated_at) SELECT id, user_id, articulo_id, contenido, created_at, updated_at FROM comentarios');

        Schema::dropIfExists('comentarios');

        DB::statement('ALTER TABLE comentarios_temp RENAME TO comentarios');
    }
};
