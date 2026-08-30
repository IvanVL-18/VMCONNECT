<?php

namespace Database\Seeders;

use App\Enums\TipoDocumento;
use App\Models\DocumentoLegal;
use Illuminate\Database\Seeder;

/**
 * Crea un registro por cada documento legal obligatorio (requisitos 1 al 8).
 *
 * Se crean SIN archivo: los PDF los entrega el despacho juridico y los sube el
 * administrador desde /admin/documentos. Quedan activos a proposito, para que
 * el sitio los liste como "pendiente de carga" en lugar de ocultarlos y dar la
 * falsa impresion de que el requisito ya esta cubierto.
 */
class DocumentoLegalSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TipoDocumento::cases() as $tipo) {
            DocumentoLegal::query()->firstOrCreate(
                ['tipo' => $tipo->value],
                [
                    'titulo' => $tipo->titulo(),
                    'archivo_path' => null,
                    'descripcion' => null,
                    'activo' => true,
                ]
            );
        }
    }
}
