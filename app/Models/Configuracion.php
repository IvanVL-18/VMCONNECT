<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton: la tabla `configuracion` siempre tiene exactamente una fila.
 * Se obtiene con Configuracion::actual().
 */
class Configuracion extends Model
{
    protected $table = 'configuracion';

    /** El singleton no se crea ni se borra desde la interfaz, solo se edita. */
    protected $guarded = ['id'];

    /**
     * Clave con la que se memoriza la fila en el contenedor.
     *
     * Se usa el contenedor y no una propiedad estatica a proposito: el
     * contenedor se reconstruye en cada peticion (y en cada prueba), asi que la
     * instancia no sobrevive de una a otra. Con una estatica, un worker de cola
     * o una prueba veria datos de la peticion anterior.
     */
    private const CLAVE_MEMORIA = 'isp.configuracion';

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'servicios_ofrecidos' => 'array',
            'medios_pago' => 'array',
            'contratacion_requisitos' => 'array',
        ];
    }

    /**
     * Devuelve la unica fila de configuracion, creandola vacia si aun no existe
     * para que el sitio nunca falle por ausencia de datos.
     */
    public static function actual(): self
    {
        $contenedor = app();

        if ($contenedor->bound(self::CLAVE_MEMORIA)) {
            return $contenedor->make(self::CLAVE_MEMORIA);
        }

        $configuracion = self::query()->firstOrCreate([]);

        $contenedor->instance(self::CLAVE_MEMORIA, $configuracion);

        return $configuracion;
    }

    /** Olvida la fila memorizada (se llama sola despues de guardar). */
    public static function olvidar(): void
    {
        app()->forgetInstance(self::CLAVE_MEMORIA);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::olvidar());
    }
}
