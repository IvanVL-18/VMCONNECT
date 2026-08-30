<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Paquetes reales de VM Connect, tomados de su material comercial.
 *
 * El material solo publica la velocidad de descarga ("HASTA 10MB"), por eso
 * `velocidad_subida` queda en null: no se inventa un dato tecnico que la
 * empresa no ha publicado. En cuanto lo definan, se captura desde el panel.
 *
 * Las caracteristicas son descripciones factuales derivadas de los propios
 * datos del paquete. Ningun texto promocional se redacta aqui.
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            ['nombre' => 'Básico', 'bajada' => 10, 'precio' => 250.00, 'destacado' => false, 'orden' => 1],
            ['nombre' => 'Estándar', 'bajada' => 15, 'precio' => 300.00, 'destacado' => false, 'orden' => 2],
            ['nombre' => 'Premium', 'bajada' => 20, 'precio' => 350.00, 'destacado' => true, 'orden' => 3],
            ['nombre' => 'Platino', 'bajada' => 30, 'precio' => 400.00, 'destacado' => false, 'orden' => 4],
        ];

        foreach ($planes as $plan) {
            Plan::query()->updateOrCreate(
                ['nombre' => $plan['nombre']],
                [
                    'velocidad_bajada' => $plan['bajada'],
                    'velocidad_subida' => null,
                    'precio_mensual' => $plan['precio'],
                    'moneda' => 'MXN',
                    'caracteristicas' => [
                        'Velocidad de descarga de hasta '.$plan['bajada'].' Mbps',
                        'Instalación por antena',
                        'Equipo en préstamo durante la vigencia del servicio',
                    ],
                    'restricciones' => '[POR DEFINIR: indicar las restricciones aplicables al paquete (requisito 12)]',
                    'folio_tarifa' => '[POR DEFINIR: folio IFT]',
                    'destacado' => $plan['destacado'],
                    'activo' => true,
                    'orden' => $plan['orden'],
                ]
            );
        }

        // Los paquetes de ejemplo del arranque inicial ya no aplican.
        Plan::query()
            ->whereIn('nombre', ['Plan Hogar 50', 'Plan Hogar 100', 'Plan Hogar 200'])
            ->delete();
    }
}
