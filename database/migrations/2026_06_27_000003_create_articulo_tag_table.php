<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulo_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->unique(['articulo_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulo_tag');
    }
};
