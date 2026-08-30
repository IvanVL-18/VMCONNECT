<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Plan;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Requisitos 12 y 13: informacion de precios y tarifas de cada paquete, con sus
 * caracteristicas, restricciones y el folio de la tarifa inscrita en el IFT.
 */
class PaquetesController extends Controller
{
    public function __invoke(): Response
    {
        $planes = Plan::query()->activos()->ordenados()->get();

        return Inertia::render('publico/paquetes', [
            'planes' => $planes->map->paraSitio()->all(),
            'nota' => Configuracion::actual()->planes_nota,
            'enlaceVisorTarifas' => collect(config('isp.enlaces_oficiales'))
                ->firstWhere('requisito', 14)['url'] ?? null,
        ]);
    }
}
