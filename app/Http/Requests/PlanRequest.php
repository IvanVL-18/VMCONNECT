<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /** El grupo de rutas ya exige sesion iniciada (middleware `auth`). */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'velocidad_bajada' => ['required', 'integer', 'min:1', 'max:100000'],
            'velocidad_subida' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'precio_mensual' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'moneda' => ['required', 'string', 'size:3'],
            'caracteristicas' => ['nullable', 'array'],
            'caracteristicas.*' => ['string', 'max:255'],
            'restricciones' => ['nullable', 'string', 'max:5000'],
            'folio_tarifa' => ['nullable', 'string', 'max:255'],
            'destacado' => ['boolean'],
            'activo' => ['boolean'],
            'orden' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del paquete',
            'velocidad_bajada' => 'velocidad de bajada',
            'velocidad_subida' => 'velocidad de subida',
            'precio_mensual' => 'precio mensual',
            'moneda' => 'moneda',
            'caracteristicas' => 'características',
            'restricciones' => 'restricciones',
            'folio_tarifa' => 'folio de tarifa',
            'orden' => 'orden de despliegue',
        ];
    }

    /**
     * El formulario manda las caracteristicas como texto (una por linea) y las
     * casillas como "0"/"1"; aqui se normalizan antes de validar.
     */
    protected function prepareForValidation(): void
    {
        $caracteristicas = $this->input('caracteristicas');

        if (is_string($caracteristicas)) {
            $caracteristicas = collect(preg_split('/\r\n|\r|\n/', $caracteristicas))
                ->map(fn (string $linea) => trim($linea))
                ->filter()
                ->values()
                ->all();
        }

        $this->merge([
            'caracteristicas' => $caracteristicas ?? [],
            'destacado' => $this->boolean('destacado'),
            'activo' => $this->boolean('activo'),
            'moneda' => strtoupper((string) $this->input('moneda', 'MXN')),
        ]);
    }
}
