<?php

namespace Tests\Feature\Publico;

use App\Models\Configuracion;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class SitioPublicoTest extends TestCase
{
    use RefreshDatabase;

    /** Todas las páginas públicas responden sin sesión iniciada. */
    public function test_las_paginas_publicas_cargan(): void
    {
        $rutas = [
            'home' => 'publico/home',
            'paquetes' => 'publico/paquetes',
            'contratacion' => 'publico/contratacion',
            'quejas' => 'publico/quejas',
            'medios-de-pago' => 'publico/medios-de-pago',
            'transparencia' => 'publico/transparencia',
            'aviso-de-privacidad' => 'publico/aviso-de-privacidad',
            'legal' => 'publico/legal',
        ];

        foreach ($rutas as $nombre => $componente) {
            $this->get(route($nombre))
                ->assertOk()
                ->assertInertia(fn (AssertableInertia $page) => $page->component($componente));
        }
    }

    /** Requisitos 12 y 13: precio, restricciones y folio viajan a la vista. */
    public function test_los_paquetes_incluyen_restricciones_y_folio(): void
    {
        Plan::create([
            'nombre' => 'Paquete de prueba',
            'velocidad_bajada' => 100,
            'velocidad_subida' => 20,
            'precio_mensual' => 599.00,
            'moneda' => 'MXN',
            'caracteristicas' => ['Una característica'],
            'restricciones' => 'Sujeto a cobertura.',
            'folio_tarifa' => 'IFT-123',
            'activo' => true,
            'orden' => 1,
        ]);

        $this->get(route('paquetes'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('publico/paquetes')
                ->has('planes', 1)
                ->where('planes.0.nombre', 'Paquete de prueba')
                // El precio viaja por JSON, así que se compara su valor
                // numérico y no su tipo exacto.
                ->where('planes.0.precio_mensual', fn ($precio) => (float) $precio === 599.0)
                ->where('planes.0.restricciones', 'Sujeto a cobertura.')
                ->where('planes.0.folio_tarifa', 'IFT-123')
            );
    }

    /** Los paquetes ocultos no deben aparecer en el sitio público. */
    public function test_los_paquetes_inactivos_no_se_publican(): void
    {
        Plan::create([
            'nombre' => 'Paquete oculto',
            'velocidad_bajada' => 10,
            'velocidad_subida' => 2,
            'precio_mensual' => 100.00,
            'activo' => false,
            'orden' => 1,
        ]);

        $this->get(route('paquetes'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page->has('planes', 0));
    }

    /** Los datos del pie de página se comparten en todas las páginas. */
    public function test_el_pie_de_pagina_recibe_los_datos_de_contacto(): void
    {
        Configuracion::actual()->update([
            'marca_comercial' => 'Marca de prueba',
            'domicilio_atencion' => 'Calle 1, Ciudad',
            'correo_facturacion' => 'facturacion@ejemplo.mx',
            'servicios_ofrecidos' => ['Internet fijo'],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('sitio.marca_comercial', 'Marca de prueba')
                ->where('sitio.domicilio_atencion', 'Calle 1, Ciudad')
                ->where('sitio.correo_facturacion', 'facturacion@ejemplo.mx')
                ->where('sitio.servicios_ofrecidos', ['Internet fijo'])
            );
    }

    /** Requisitos 14, 15 y 16: las ligas oficiales se publican individualmente. */
    public function test_transparencia_publica_las_ligas_oficiales(): void
    {
        $this->get(route('transparencia'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('enlaces', 3)
                ->where('enlaces.0.url', 'https://tarifas.ift.org.mx/ift_visor/')
                ->where('enlaces.1.url', 'https://dof.gob.mx/nota_detalle.php?codigo=55873')
            );
    }

    /**
     * El título y la descripción deben venir en el HTML inicial, no inyectados
     * por React: un rastreador que no ejecute JavaScript no los vería.
     */
    public function test_cada_pagina_trae_su_seo_en_el_html(): void
    {
        $respuesta = $this->get(route('paquetes'))->assertOk();

        $respuesta->assertSee('<title>Paquetes y tarifas - '.config('app.name').'</title>', escape: false);
        $respuesta->assertSee('name="description"', escape: false);
        $respuesta->assertSee('rel="canonical"', escape: false);
        $respuesta->assertSee('property="og:title"', escape: false);
        $respuesta->assertDontSee('name="robots"', escape: false);
    }

    /** El panel y el login no deben aparecer en buscadores. */
    public function test_las_pantallas_privadas_llevan_noindex(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('content="noindex, nofollow"', escape: false);
    }

    /** El sitemap lista las páginas públicas y no expone el panel. */
    public function test_el_sitemap_lista_las_paginas_publicas(): void
    {
        $respuesta = $this->get('/sitemap.xml');

        $respuesta->assertOk();
        $respuesta->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $respuesta->assertSee(route('paquetes'), escape: false);
        $respuesta->assertSee(route('legal'), escape: false);
        $respuesta->assertDontSee('/admin', escape: false);
    }
}
