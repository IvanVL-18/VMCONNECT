<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
{
    /** El grupo de rutas ya exige sesion iniciada (middleware `auth`). */
    public function authorize(): bool
    {
        return true;
    }

    /** Campos que el formulario manda como texto y se guardan como lista. */
    private const LISTAS = [
        'servicios_ofrecidos',
        'medios_pago',
        'contratacion_requisitos',
    ];

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'marca_comercial' => ['nullable', 'string', 'max:255'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'domicilio_atencion' => ['nullable', 'string', 'max:1000'],
            'horario_oficina' => ['nullable', 'string', 'max:255'],
            'telefono_atencion' => ['nullable', 'string', 'max:50'],
            'correo_atencion' => ['nullable', 'email', 'max:255'],
            'correo_facturacion' => ['nullable', 'email', 'max:255'],

            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],

            'servicios_ofrecidos' => ['nullable', 'array'],
            'servicios_ofrecidos.*' => ['string', 'max:255'],

            'medios_pago' => ['nullable', 'array'],
            'medios_pago.*' => ['string', 'max:255'],
            'medios_pago_nota' => ['nullable', 'string', 'max:2000'],

            'contratacion_procedimiento' => ['nullable', 'string', 'max:5000'],
            'contratacion_requisitos' => ['nullable', 'array'],
            'contratacion_requisitos.*' => ['string', 'max:255'],
            'contratacion_lugar' => ['nullable', 'string', 'max:1000'],
            'contratacion_horario' => ['nullable', 'string', 'max:255'],

            'quejas_procedimiento' => ['nullable', 'string', 'max:5000'],
            'quejas_domicilio' => ['nullable', 'string', 'max:1000'],
            'quejas_telefono' => ['nullable', 'string', 'max:50'],
            'quejas_correo' => ['nullable', 'email', 'max:255'],
            'quejas_horario' => ['nullable', 'string', 'max:255'],
            'quejas_tiempo_promedio' => ['nullable', 'string', 'max:255'],
            'quejas_tiempo_maximo' => ['nullable', 'string', 'max:255'],

            'home_titulo' => ['nullable', 'string', 'max:255'],
            'home_subtitulo' => ['nullable', 'string', 'max:1000'],
            'empresa_descripcion' => ['nullable', 'string', 'max:5000'],
            'planes_nota' => ['nullable', 'string', 'max:2000'],
            'planes_nota_fibra' => ['nullable', 'string', 'max:2000'],
            'planes_nota_antena' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'marca_comercial' => 'marca comercial',
            'razon_social' => 'razón social',
            'domicilio_atencion' => 'domicilio de atención',
            'horario_oficina' => 'horario de oficina',
            'telefono_atencion' => 'teléfono de atención',
            'correo_atencion' => 'correo de atención',
            'correo_facturacion' => 'correo de facturación',
            'facebook_url' => 'URL de Facebook',
            'instagram_url' => 'URL de Instagram',
            'servicios_ofrecidos' => 'servicios ofrecidos',
            'medios_pago' => 'medios de pago',
            'medios_pago_nota' => 'nota sobre medios de pago',
            'contratacion_procedimiento' => 'procedimiento de contratación',
            'contratacion_requisitos' => 'requisitos de contratación',
            'contratacion_lugar' => 'lugar de contratación',
            'contratacion_horario' => 'horario de contratación',
            'quejas_procedimiento' => 'procedimiento de quejas',
            'quejas_domicilio' => 'domicilio para quejas',
            'quejas_telefono' => 'teléfono de quejas',
            'quejas_correo' => 'correo de quejas',
            'quejas_horario' => 'horario de atención de quejas',
            'quejas_tiempo_promedio' => 'tiempo promedio de resolución',
            'quejas_tiempo_maximo' => 'tiempo máximo de resolución',
            'home_titulo' => 'título de la página de inicio',
            'home_subtitulo' => 'texto de apoyo de la página de inicio',
            'empresa_descripcion' => 'descripción de la empresa',
            'planes_nota' => 'nota general sobre los paquetes',
            'planes_nota_fibra' => 'nota de los paquetes de fibra',
            'planes_nota_antena' => 'nota de los paquetes de antena',
        ];
    }

    /** Los campos de lista llegan como texto con un elemento por linea. */
    protected function prepareForValidation(): void
    {
        $normalizados = [];

        foreach (self::LISTAS as $campo) {
            $valor = $this->input($campo);

            if (is_string($valor)) {
                $valor = collect(preg_split('/\r\n|\r|\n/', $valor))
                    ->map(fn (string $linea) => trim($linea))
                    ->filter()
                    ->values()
                    ->all();
            }

            $normalizados[$campo] = $valor ?? [];
        }

        $this->merge($normalizados);
    }
}
