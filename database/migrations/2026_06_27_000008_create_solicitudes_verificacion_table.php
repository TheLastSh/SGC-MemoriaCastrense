<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_verificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo', ['historiador', 'cultor', 'cronista']);
            $table->string('documento_path')->nullable();
            $table->text('resena_curricular');
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamp('fecha_verificacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_verificacion');
    }
};
