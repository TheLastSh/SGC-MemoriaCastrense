<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hilos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('contenido_inicial');
            $table->foreignId('autor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('foro_categorias')->cascadeOnDelete();
            $table->enum('status', ['abierto', 'cerrado'])->default('abierto');
            $table->boolean('fijado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hilos');
    }
};
