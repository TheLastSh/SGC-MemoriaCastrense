<?php

namespace Database\Factories;

use App\Models\ForoCategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ForoCategoriaFactory extends Factory
{
    protected $model = ForoCategoria::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->word().' History',
            'descripcion' => fake()->sentence(),
            'orden' => fake()->numberBetween(1, 10),
        ];
    }
}
