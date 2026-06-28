<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hilo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('autor_id')->constrained('users')->cascadeOnDelete();
            $table->text('contenido');
            $table->boolean('editado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
