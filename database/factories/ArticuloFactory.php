<?php

namespace Database\Factories;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticuloFactory extends Factory
{
    protected $model = Articulo::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(5),
            'extracto' => fake()->paragraph(2),
            'contenido' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'status' => 'borrador',
            'author_id' => User::factory(),
            'categoria_id' => Categoria::factory(),
            'visitas' => fake()->numberBetween(0, 500),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'publicado',
            'fecha_publicacion' => now(),
        ]);
    }
}
