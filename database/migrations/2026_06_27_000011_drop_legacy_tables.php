<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('archivos');
        Schema::dropIfExists('marcadores');
        Schema::dropIfExists('registros_patrimoniales');
    }

    public function down(): void
    {
        // No revertimos — las tablas legacy no se recuperan
    }
};
