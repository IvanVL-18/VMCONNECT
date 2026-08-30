<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'velocidad_bajada',
        'velocidad_subida',
        'precio_mensual',
        'moneda',
        'caracteristicas',
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
            'velocidad_bajada' => 'integer',
            'velocidad_subida' => 'integer',
            'precio_mensual' => 'decimal:2',
            'caracteristicas' => 'array',
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
            'velocidad_bajada' => $this->velocidad_bajada,
            'velocidad_subida' => $this->velocidad_subida,
            'precio_mensual' => (float) $this->precio_mensual,
            'moneda' => $this->moneda,
            'caracteristicas' => $this->caracteristicas ?? [],
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
}
