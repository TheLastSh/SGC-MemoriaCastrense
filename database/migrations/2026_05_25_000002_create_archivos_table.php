<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->uuid('registro_id');
            $table->foreign('registro_id')->references('id')->on('registros_patrimoniales')->onDelete('cascade');
            $table->text('url_recurso');
            $table->string('nombre_original', 255);
            $table->string('tipo_archivo', 50);
            $table->integer('peso_archivo_kb')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
