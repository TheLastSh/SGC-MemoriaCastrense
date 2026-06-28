<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('extracto')->nullable();
            $table->longText('contenido');
            $table->string('portada_url')->nullable();
            $table->enum('status', ['borrador', 'revision', 'publicado'])->default('borrador');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->date('fecha_publicacion')->nullable();
            $table->integer('visitas')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
