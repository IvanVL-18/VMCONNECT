<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfiguracionRequest;
use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ConfiguracionController extends Controller
{
    public function edit(): Response
    {
        $configuracion = Configuracion::actual();

        return Inertia::render('admin/configuracion', [
            'configuracion' => $configuracion->only([
                'marca_comercial',
                'razon_social',
                'domicilio_atencion',
                'horario_oficina',
                'telefono_atencion',
                'correo_atencion',
                'correo_facturacion',
                'facebook_url',
                'instagram_url',
                'whatsapp',
                'servicios_ofrecidos',
                'medios_pago',
                'medios_pago_nota',
                'contratacion_procedimiento',
                'contratacion_requisitos',
                'contratacion_lugar',
                'contratacion_horario',
                'quejas_procedimiento',
                'quejas_domicilio',
                'quejas_telefono',
                'quejas_correo',
                'quejas_horario',
                'quejas_tiempo_promedio',
                'quejas_tiempo_maximo',
                'home_titulo',
                'home_subtitulo',
                'empresa_descripcion',
                'planes_nota',
                'planes_nota_fibra',
                'planes_nota_antena',
            ]),
        ]);
    }

    public function update(ConfiguracionRequest $request): RedirectResponse
    {
        Configuracion::actual()->update($request->validated());

        return to_route('admin.configuracion.edit')
            ->with('exito', 'Configuración guardada correctamente.');
    }
}
