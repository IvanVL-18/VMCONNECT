<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Sitemap XML basico. Solo lista las paginas publicas; el panel queda fuera a
 * proposito para no exponerlo a los buscadores.
 */
class SitemapController extends Controller
{
    /** Rutas publicas con su prioridad y frecuencia de cambio estimadas. */
    private const PAGINAS = [
        ['ruta' => 'home', 'prioridad' => '1.0', 'frecuencia' => 'weekly'],
        ['ruta' => 'paquetes', 'prioridad' => '0.9', 'frecuencia' => 'weekly'],
        ['ruta' => 'contratacion', 'prioridad' => '0.7', 'frecuencia' => 'monthly'],
        ['ruta' => 'medios-de-pago', 'prioridad' => '0.7', 'frecuencia' => 'monthly'],
        ['ruta' => 'quejas', 'prioridad' => '0.7', 'frecuencia' => 'monthly'],
        ['ruta' => 'transparencia', 'prioridad' => '0.6', 'frecuencia' => 'monthly'],
        ['ruta' => 'legal', 'prioridad' => '0.6', 'frecuencia' => 'monthly'],
        ['ruta' => 'aviso-de-privacidad', 'prioridad' => '0.5', 'frecuencia' => 'yearly'],
    ];

    public function __invoke(): Response
    {
        $xml = view('sitemap', ['paginas' => self::PAGINAS])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
