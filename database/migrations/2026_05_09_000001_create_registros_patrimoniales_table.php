<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_patrimoniales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->date('fecha_suceso');
            $table->text('url_recurso');

            $table->string('tipo_archivo', 50)->nullable();
            $table->integer('peso_archivo_kb')->nullable();

            $table->foreignId('id_categoria')->constrained('categorias');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_patrimoniales');
    }
};
