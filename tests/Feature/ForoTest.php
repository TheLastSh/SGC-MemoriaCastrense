<?php

namespace Tests\Feature;

use App\Models\ForoCategoria;
use App\Models\Hilo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $publicador;

    private User $usuario;

    private ForoCategoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'administrador']);
        $this->publicador = User::factory()->create(['role' => 'publicador']);
        $this->usuario = User::factory()->create(['role' => 'usuario']);
        $this->categoria = ForoCategoria::create(['nombre' => 'Discusión General', 'descripcion' => 'Test']);
    }

    public function test_foro_index_lists_categories(): void
    {
        $response = $this->get(route('foro.index'));

        $response->assertStatus(200);
        $response->assertSee('Discusión General');
    }

    public function test_authenticated_user_can_create_hilo(): void
    {
        $response = $this->actingAs($this->usuario)
            ->post(route('foro.store-hilo', $this->categoria), [
                'titulo' => 'Hilo de prueba',
                'contenido_inicial' => 'Este es el contenido inicial del hilo para probar.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hilos', ['titulo' => 'Hilo de prueba']);
    }

    public function test_hilo_shows_respuestas(): void
    {
        $hilo = Hilo::factory()->create([
            'autor_id' => $this->usuario->id,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->get(route('foro.hilo', $hilo));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_create_hilo(): void
    {
        $response = $this->post(route('foro.store-hilo', $this->categoria), [
            'titulo' => 'Hilo sin auth',
            'contenido_inicial' => 'Contenido',
        ]);

        $response->assertRedirect(route('login'));
    }
}
