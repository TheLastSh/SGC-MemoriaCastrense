<?php

namespace Database\Seeders;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\ForoCategoria;
use App\Models\Hilo;
use App\Models\Respuesta;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@memoriacastrense.gob.ve',
            'password' => bcrypt('Password123'),
            'role' => 'administrador',
        ]);

        $publicador = User::factory()->create([
            'name' => 'Publicador',
            'email' => 'publicador@memoriacastrense.gob.ve',
            'password' => bcrypt('Password123'),
            'role' => 'publicador',
        ]);

        User::factory()->create([
            'name' => 'Usuario',
            'email' => 'usuario@memoriacastrense.gob.ve',
            'password' => bcrypt('Password123'),
            'role' => 'usuario',
        ]);

        $categorias = collect([
            ['nombre' => 'Fotografías Históricas', 'descripcion' => 'Material fotográfico de época militar y civil.'],
            ['nombre' => 'Documentos Oficiales', 'descripcion' => 'Actas, oficios, tratados y correspondencia castrense.'],
            ['nombre' => 'Condecoraciones y Medallas', 'descripcion' => 'Registros de honor al mérito e insignias.'],
            ['nombre' => 'Mapas Estratégicos', 'descripcion' => 'Cartografía antigua y planos tácticos.'],
        ])->map(fn ($c) => Categoria::create($c));

        $tags = collect([
            'Independencia', 'Batalla Naval', 'Siglo XIX', 'Fortificaciones',
            'Guerra Federal', 'Caudillismo', 'Vela de Coro', 'Colonia',
        ])->map(fn ($nombre) => Tag::create(['nombre' => $nombre]));

        $foroCategorias = collect([
            ['nombre' => 'Documentos Históricos', 'descripcion' => 'Discusión sobre documentos, tratados, actas y manuscritos de la historia militar de La Vela de Coro.', 'orden' => 1],
            ['nombre' => 'Hechos Históricos', 'descripcion' => 'Debate y análisis de eventos, batallas y personajes históricos de la región.', 'orden' => 2],
        ])->map(fn ($c) => ForoCategoria::create($c));

        $foroDocs = $foroCategorias->first();
        $foroHechos = $foroCategorias->last();

        $hilo1 = Hilo::create([
            'titulo' => 'El Castillo de San Juan de los Cayos',
            'contenido_inicial' => 'Comparto información sobre esta fortificación del siglo XVIII ubicada en La Vela de Coro. ¿Alguien tiene planos originales o documentos de su construcción?',
            'autor_id' => $publicador->id,
            'categoria_id' => $foroDocs->id,
            'status' => 'abierto',
            'fijado' => true,
        ]);

        Hilo::create([
            'titulo' => 'La Batalla de la Vela de Coro (1810)',
            'contenido_inicial' => 'Se sabe que hubo un enfrentamiento importante en 1810 durante los inicios de la Independencia. Me gustaría recopilar fuentes primarias sobre este hecho.',
            'autor_id' => $publicador->id,
            'categoria_id' => $foroHechos->id,
            'status' => 'abierto',
            'fijado' => false,
        ]);

        Respuesta::create([
            'hilo_id' => $hilo1->id,
            'autor_id' => $admin->id,
            'contenido' => 'Hay algunos mapas en el Archivo General de la Nación que podrían ser útiles. Voy a digitalizarlos y subirlos a la biblioteca.',
        ]);

        $articulo1 = Articulo::create([
            'titulo' => 'Las Fortificaciones de La Vela de Coro en el Siglo XVIII',
            'extracto' => 'Un recorrido por las estructuras defensivas construidas en la costa coriana durante el período colonial tardío.',
            'contenido' => '<p>La Vela de Coro, puerto principal de la Provincia de Coro durante la colonia, contó con varias fortificaciones destinadas a proteger la costa de ataques piratas y corsarios.</p><p>El <strong>Castillo de San Juan de los Cayos</strong> fue la principal edificación defensiva, construida a finales del siglo XVIII con piedra coralina y calicanto.</p><p>Hoy en día, solo quedan ruinas de esta estructura, pero los documentos de la época nos permiten reconstruir su diseño original.</p>',
            'status' => 'publicado',
            'author_id' => $publicador->id,
            'categoria_id' => $categorias->first()->id,
            'fecha_publicacion' => now(),
        ]);

        $articulo2 = Articulo::create([
            'titulo' => 'La Participación de Coro en la Guerra de Independencia',
            'extracto' => 'Análisis del rol de la región coriana en la gesta emancipadora venezolana.',
            'contenido' => '<p>Coro fue una de las primeras provincias en proclamar la independencia de Venezuela en 1810. Sin embargo, su posición geográfica y la influencia realista en la región hicieron que fuera también uno de los últimos bastiones en caer.</p><p>Los archivos parroquiales de La Vela de Coro contienen registros de los soldados que partieron a la guerra, muchos de los cuales nunca regresaron.</p>',
            'status' => 'publicado',
            'author_id' => $publicador->id,
            'categoria_id' => $categorias->get(1)->id,
            'fecha_publicacion' => now()->subDay(),
        ]);

        $articulo1->tags()->attach($tags->whereIn('nombre', ['Fortificaciones', 'Colonia', 'Siglo XIX'])->pluck('id'));
        $articulo2->tags()->attach($tags->whereIn('nombre', ['Independencia', 'Batalla Naval', 'Caudillismo'])->pluck('id'));
    }
}
