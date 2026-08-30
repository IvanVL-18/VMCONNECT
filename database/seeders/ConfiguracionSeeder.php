<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

/**
 * Configuracion del sitio.
 *
 * Se capturan los datos que VM Connect ya publica en su material comercial
 * (marca, medios de pago, nota de instalacion). Todo lo demas queda como
 * "[POR DEFINIR: ...]" a proposito: la normativa (PROFECO) sanciona la
 * publicidad enganosa, asi que ningun texto promocional ni dato de contacto se
 * inventa aqui. Lo captura el cliente desde /admin/configuracion.
 */
class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $configuracion = Configuracion::query()->firstOrCreate([]);

        $configuracion->fill([
            'marca_comercial' => 'VM Connect',
            'razon_social' => '[POR DEFINIR: razón social]',
            'domicilio_atencion' => '[POR DEFINIR: domicilio de la oficina de atención a clientes]',
            'horario_oficina' => '[POR DEFINIR: horario de oficina]',
            'telefono_atencion' => '[POR DEFINIR: teléfono]',
            'correo_atencion' => '[POR DEFINIR: correo de atención]',
            'correo_facturacion' => '[POR DEFINIR: correo de facturación]',

            'facebook_url' => null,
            'instagram_url' => null,
            'whatsapp' => null,

            // Requisito 10: catalogo de tramites / servicios ofrecidos
            'servicios_ofrecidos' => [
                'Servicio de acceso a Internet fijo inalámbrico',
            ],

            // Requisito 11: medios de pago. Tomados del material que la empresa
            // ya difunde; faltan los demas por capturar.
            'medios_pago' => [
                'Pago en OXXO con número de tarjeta BBVA: 4152 3144 4308 5439',
                'Pago en OXXO con número de tarjeta BBVA: 4152 3143 8131 5715',
                '[POR DEFINIR: agregar los demás medios de pago]',
            ],
            'medios_pago_nota' => 'Recuerda enviar tu comprobante de pago después de realizarlo.',

            // Requisito 18: contratacion
            'contratacion_procedimiento' => '[POR DEFINIR: describir el procedimiento de contratación paso a paso]',
            'contratacion_requisitos' => [
                '[POR DEFINIR: requisito de contratación 1]',
                '[POR DEFINIR: requisito de contratación 2]',
            ],
            'contratacion_lugar' => '[POR DEFINIR: lugar donde se contrata el servicio]',
            'contratacion_horario' => '[POR DEFINIR: horario de contratación]',

            // Requisito 20: quejas
            'quejas_procedimiento' => '[POR DEFINIR: describir cómo se presenta una queja]',
            'quejas_domicilio' => '[POR DEFINIR: domicilio para presentar quejas]',
            'quejas_telefono' => '[POR DEFINIR: teléfono de quejas]',
            'quejas_correo' => '[POR DEFINIR: correo de quejas]',
            'quejas_horario' => '[POR DEFINIR: horario de atención de quejas]',
            'quejas_tiempo_promedio' => '[POR DEFINIR: tiempo promedio de resolución]',
            'quejas_tiempo_maximo' => '[POR DEFINIR: tiempo máximo de resolución]',

            // Textos del sitio publico. Solo se capturan los factuales; el resto
            // lo aprueba el cliente.
            'home_titulo' => 'VM Connect',
            'home_subtitulo' => 'Servicio de Internet fijo inalámbrico por antena.',
            'empresa_descripcion' => '[POR DEFINIR: descripción de la empresa]',
            'planes_nota' => 'La instalación por antena tiene un costo de $500 e incluye el primer mes de servicio. El equipo se queda en préstamo.',
        ]);

        $configuracion->save();
    }
}
