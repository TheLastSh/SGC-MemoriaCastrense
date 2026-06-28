<?php

namespace Tests\Feature;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticuloTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $publicador;

    private User $usuario;

    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'administrador']);
        $this->publicador = User::factory()->create(['role' => 'publicador']);
        $this->usuario = User::factory()->create(['role' => 'usuario']);
        $this->categoria = Categoria::create(['nombre' => 'Test Category', 'descripcion' => 'Desc']);
    }

    public function test_catalogo_publico_returns_paginated_articulos(): void
    {
        Articulo::factory(5)->create([
            'status' => 'publicado',
            'author_id' => $this->publicador->id,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->get(route('articulos.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Category');
    }

    public function test_publicador_can_create_articulo(): void
    {
        $tag = Tag::create(['nombre' => 'Historia']);

        $response = $this->actingAs($this->publicador)
            ->post(route('articulos.store'), [
                'titulo' => 'Nuevo Artículo',
                'contenido' => '<p>Contenido del artículo de prueba.</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'publicado',
                'tags' => [$tag->id],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articulos', ['titulo' => 'Nuevo Artículo']);
    }

    public function test_usuario_cannot_create_articulo(): void
    {
        $response = $this->actingAs($this->usuario)
            ->get(route('articulos.create'));

        $response->assertForbidden();
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->publicador)
            ->post(route('articulos.store'), []);

        $response->assertSessionHasErrors(['titulo', 'contenido', 'categoria_id']);
    }

    public function test_articulo_can_be_updated(): void
    {
        $articulo = Articulo::factory()->create([
            'author_id' => $this->publicador->id,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->publicador)
            ->put(route('articulos.update', $articulo), [
                'titulo' => 'Título Actualizado',
                'contenido' => '<p>Contenido actualizado.</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'publicado',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articulos', ['id' => $articulo->id, 'titulo' => 'Título Actualizado']);
    }

    public function test_articulo_can_be_soft_deleted_by_admin(): void
    {
        $articulo = Articulo::factory()->create([
            'author_id' => $this->publicador->id,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('articulos.destroy', $articulo));

        $response->assertRedirect();
        $this->assertSoftDeleted($articulo);
    }
}
