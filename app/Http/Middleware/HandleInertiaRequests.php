<?php

namespace App\Http\Middleware;

use App\Models\Configuracion;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',

            'flash' => [
                'exito' => $request->session()->get('exito'),
            ],

            // Base para las URL canonicas y las etiquetas Open Graph.
            'appUrl' => rtrim((string) config('app.url'), '/'),

            // SEO de la pagina actual. Se genera en el servidor para que el
            // HTML inicial ya traiga titulo, descripcion y Open Graph.
            'seo' => Seo::paraPeticion($request),

            // Datos que el pie de pagina necesita en TODAS las paginas publicas
            // (domicilio, horario, correos, telefono, redes, marca comercial y
            // servicios ofrecidos). Son obligatorios por normativa.
            'sitio' => fn () => $this->datosDelSitio(),
        ];
    }

    /**
     * Datos de contacto e identidad que se muestran en el pie de pagina.
     *
     * @return array<string, mixed>
     */
    private function datosDelSitio(): array
    {
        $configuracion = Configuracion::actual();

        return [
            'marca_comercial' => $configuracion->marca_comercial,
            'razon_social' => $configuracion->razon_social,
            'domicilio_atencion' => $configuracion->domicilio_atencion,
            'horario_oficina' => $configuracion->horario_oficina,
            'telefono_atencion' => $configuracion->telefono_atencion,
            'correo_atencion' => $configuracion->correo_atencion,
            'correo_facturacion' => $configuracion->correo_facturacion,
            'facebook_url' => $configuracion->facebook_url,
            'instagram_url' => $configuracion->instagram_url,
            'whatsapp' => $configuracion->whatsapp,
            'servicios_ofrecidos' => $configuracion->servicios_ofrecidos ?? [],
        ];
    }
}
