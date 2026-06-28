<?php

namespace Database\Factories;

use App\Models\ForoCategoria;
use App\Models\Hilo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HiloFactory extends Factory
{
    protected $model = Hilo::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'contenido_inicial' => fake()->paragraphs(2, true),
            'autor_id' => User::factory(),
            'categoria_id' => ForoCategoria::factory(),
            'status' => 'abierto',
            'fijado' => false,
        ];
    }
}
