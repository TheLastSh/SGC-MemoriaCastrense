<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('registro_id');
            $table->foreign('registro_id')->references('id')->on('registros_patrimoniales')->onDelete('cascade');
            $table->timestamps();

            // Un usuario solo puede guardar un registro una vez
            $table->unique(['user_id', 'registro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marcadores');
    }
};
