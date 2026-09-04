<?php

namespace App\Models;

use App\Enums\TipoDocumento;
use Database\Factories\DocumentoLegalFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentoLegal extends Model
{
    /** @use HasFactory<DocumentoLegalFactory> */
    use HasFactory;

    protected $table = 'documentos_legales';

    protected $fillable = [
        'tipo',
        'titulo',
        'archivo_path',
        'archivo_nombre_original',
        'archivo_bytes',
        'descripcion',
        'activo',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'tipo' => TipoDocumento::class,
            'archivo_bytes' => 'integer',
            'activo' => 'boolean',
        ];
    }

    /** Un documento solo es descargable si esta activo y tiene archivo cargado. */
    public function estaDisponible(): bool
    {
        return $this->activo
            && filled($this->archivo_path)
            && Storage::disk(config('isp.disco_documentos'))->exists($this->archivo_path);
    }

    /** Borra el archivo del disco, si existe. */
    public function eliminarArchivo(): void
    {
        if (blank($this->archivo_path)) {
            return;
        }

        Storage::disk(config('isp.disco_documentos'))->delete($this->archivo_path);
    }

    /** @param  Builder<DocumentoLegal>  $query */
    public function scopeActivos(Builder $query): void
    {
        $query->where('activo', true);
    }
}
