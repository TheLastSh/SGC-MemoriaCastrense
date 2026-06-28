<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subido_por')->constrained('users')->cascadeOnDelete();
            $table->string('nombre_original');
            $table->string('filename');
            $table->string('mime_type');
            $table->integer('peso_kb')->default(0);
            $table->integer('ancho')->nullable();
            $table->integer('alto')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('coleccion'); // imagen, video, documento
            $table->json('metadatos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
