<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

/**
 * Configuracion del sitio.
 *
 * Se capturan los datos que VM Connect ya publica en su material comercial
 * (marca, medios de pago, notas de instalacion). Todo lo demas queda como
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

            // Numero que la empresa difunde en su material comercial. Es el
            // canal por el que se atienden los pagos, asi que alimenta tanto el
            // pie de pagina como el boton de /medios-de-pago.
            'whatsapp' => '+52 1 771 811 5855',

            // Requisito 10: catalogo de tramites / servicios ofrecidos
            'servicios_ofrecidos' => [
                'Servicio de acceso a Internet fijo por fibra óptica',
                'Servicio de acceso a Internet fijo inalámbrico (antena)',
            ],

            // Requisito 11: medios de pago. La empresa no acepta tarjeta de
            // credito, debito ni Visa: el pago se coordina por WhatsApp con
            // atencion a clientes.
            'medios_pago' => [
                'Pago por WhatsApp con el área de atención a clientes',
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
            'home_subtitulo' => 'Servicio de Internet fijo por fibra óptica y por antena.',
            'empresa_descripcion' => '[POR DEFINIR: descripción de la empresa]',

            // Cada red tiene su propia condicion de instalacion, por eso la nota
            // va separada: publicarlas juntas haria que una contradijera a la
            // otra. La nota general queda vacia hasta que exista una condicion
            // que aplique a todo el catalogo.
            'planes_nota' => null,
            'planes_nota_fibra' => 'Los paquetes de fibra óptica no tienen costo de instalación.',
            'planes_nota_antena' => 'La instalación por antena tiene un costo de $500 e incluye el primer mes de servicio. El equipo se queda en préstamo.',
        ]);

        $configuracion->save();
    }
}
