<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Plan;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $configuracion = Configuracion::actual();

        $destacados = Plan::query()
            ->activos()
            ->ordenados()
            ->where('destacado', true)
            ->get();

        // Si nadie ha marcado planes como destacados, mostramos los primeros
        // para que la portada nunca quede vacia.
        if ($destacados->isEmpty()) {
            $destacados = Plan::query()->activos()->ordenados()->limit(3)->get();
        }

        return Inertia::render('publico/home', [
            'titulo' => $configuracion->home_titulo,
            'subtitulo' => $configuracion->home_subtitulo,
            'descripcionEmpresa' => $configuracion->empresa_descripcion,
            'serviciosOfrecidos' => $configuracion->servicios_ofrecidos ?? [],
            'planes' => $destacados->map->paraSitio()->all(),
            'notaPlanes' => $configuracion->planes_nota,
        ]);
    }
}
