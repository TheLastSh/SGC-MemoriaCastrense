<?php

namespace Tests\Unit;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\User;
use App\Services\ArticuloService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ArticuloServiceTest extends TestCase
{
    use RefreshDatabase;

    private ArticuloService $service;

    private User $user;

    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ArticuloService::class);
        $this->user = User::factory()->create(['role' => 'publicador']);
        $this->categoria = Categoria::factory()->create();
    }

    public function test_publicar_articulo_creates_article_with_basic_data(): void
    {
        $articulo = $this->service->publicarArticulo(
            [
                'titulo' => 'Artículo de Prueba',
                'extracto' => 'Extracto de prueba',
                'contenido' => '<p>Contenido del artículo</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'publicado',
            ],
            [],
            null,
            $this->user->id
        );

        $this->assertInstanceOf(Articulo::class, $articulo);
        $this->assertDatabaseHas('articulos', [
            'id' => $articulo->id,
            'titulo' => 'Artículo de Prueba',
            'author_id' => $this->user->id,
            'categoria_id' => $this->categoria->id,
        ]);
    }

    public function test_publicar_articulo_sets_publication_date_when_published(): void
    {
        $articulo = $this->service->publicarArticulo(
            [
                'titulo' => 'Artículo Publicado',
                'contenido' => '<p>Contenido</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'publicado',
            ],
            [],
            null,
            $this->user->id
        );

        $this->assertNotNull($articulo->fecha_publicacion);
    }

    public function test_publicar_articulo_does_not_set_date_when_draft(): void
    {
        $articulo = $this->service->publicarArticulo(
            [
                'titulo' => 'Artículo Borrador',
                'contenido' => '<p>Contenido</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'borrador',
            ],
            [],
            null,
            $this->user->id
        );

        $this->assertNull($articulo->fecha_publicacion);
    }

    public function test_publicar_articulo_syncs_tags(): void
    {
        $tags = Tag::factory(3)->create();

        $articulo = $this->service->publicarArticulo(
            [
                'titulo' => 'Artículo con Tags',
                'contenido' => '<p>Contenido</p>',
                'categoria_id' => $this->categoria->id,
                'status' => 'borrador',
            ],
            $tags->pluck('id')->toArray(),
            null,
            $this->user->id
        );

        $this->assertCount(3, $articulo->tags);
    }

    public function test_publicar_articulo_throws_exception_on_failure(): void
    {
        $this->expectException(\Exception::class);

        $this->service->publicarArticulo(
            [
                'titulo' => 'Fallo',
                'contenido' => '<p>Contenido</p>',
                'categoria_id' => 99999,
                'status' => 'publicado',
            ],
            [],
            null,
            $this->user->id
        );
    }

    public function test_subir_media_creates_media_record(): void
    {
        $archivo = UploadedFile::fake()->image('test-image.jpg', 800, 600);

        $media = $this->service->subirMedia(
            [
                'coleccion' => 'imagen',
                'alt_text' => 'Texto alternativo',
                'descripcion' => 'Descripción de prueba',
            ],
            $archivo,
            $this->user->id
        );

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'subido_por' => $this->user->id,
            'nombre_original' => 'test-image.jpg',
            'coleccion' => 'imagen',
            'alt_text' => 'Texto alternativo',
            'descripcion' => 'Descripción de prueba',
        ]);
    }
}
