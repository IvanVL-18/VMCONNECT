<?php

namespace App\Models;

use App\Enums\Tecnologia;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * El cast a enum se declara tambien como propiedad para que el analisis
 * estatico lo vea: `casts()` devuelve un class-string y PHPStan no puede
 * deducir de ahi que `$plan->tecnologia` es un Tecnologia y no un string.
 *
 * @property Tecnologia $tecnologia
 */
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'tecnologia',
        'velocidad_bajada',
        'velocidad_subida',
        'precio_mensual',
        'moneda',
        'caracteristicas',
        'incluye_tv',
        'incluye_camara',
        'restricciones',
        'folio_tarifa',
        'destacado',
        'activo',
        'orden',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'tecnologia' => Tecnologia::class,
            'velocidad_bajada' => 'integer',
            'velocidad_subida' => 'integer',
            'precio_mensual' => 'decimal:2',
            'caracteristicas' => 'array',
            'incluye_tv' => 'boolean',
            'incluye_camara' => 'boolean',
            'destacado' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    /**
     * Forma en que el plan se entrega al sitio publico.
     *
     * Incluye siempre las restricciones (requisito 12) y el folio de la tarifa
     * inscrita en el IFT (requisito 13), que deben mostrarse junto al precio.
     *
     * @return array<string, mixed>
     */
    public function paraSitio(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tecnologia' => $this->tecnologia->value,
            'tecnologia_etiqueta' => $this->tecnologia->etiqueta(),
            'velocidad_bajada' => $this->velocidad_bajada,
            'velocidad_subida' => $this->velocidad_subida,
            'precio_mensual' => (float) $this->precio_mensual,
            'moneda' => $this->moneda,
            'caracteristicas' => $this->caracteristicas ?? [],
            'incluye_tv' => $this->incluye_tv,
            'incluye_camara' => $this->incluye_camara,
            'restricciones' => $this->restricciones,
            'folio_tarifa' => $this->folio_tarifa,
            'destacado' => $this->destacado,
        ];
    }

    /** @param  Builder<Plan>  $query */
    public function scopeActivos(Builder $query): void
    {
        $query->where('activo', true);
    }

    /** @param  Builder<Plan>  $query */
    public function scopeOrdenados(Builder $query): void
    {
        $query->orderBy('orden')->orderBy('id');
    }

    /** @param  Builder<Plan>  $query */
    public function scopeDeTecnologia(Builder $query, Tecnologia $tecnologia): void
    {
        $query->where('tecnologia', $tecnologia->value);
    }
}
