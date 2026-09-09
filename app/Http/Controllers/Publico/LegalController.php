<?php

namespace App\Http\Controllers\Publico;

use App\Enums\TipoDocumento;
use App\Http\Controllers\Controller;
use App\Models\DocumentoLegal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Centro de descargas de los documentos legales (requisitos 1 al 8).
 */
class LegalController extends Controller
{
    public function index(): Response
    {
        $documentos = DocumentoLegal::query()
            ->activos()
            ->get()
            ->sortBy(fn (DocumentoLegal $documento) => $documento->tipo->requisito())
            ->map(fn (DocumentoLegal $documento) => [
                'tipo' => $documento->tipo->value,
                'titulo' => $documento->titulo,
                'descripcion' => $documento->descripcion,
                'requisito' => $documento->tipo->requisito(),
                'disponible' => $documento->estaDisponible(),
                'tamano_bytes' => $documento->archivo_bytes,
            ])
            ->values()
            ->all();

        return Inertia::render('publico/legal', [
            'documentos' => $documentos,
        ]);
    }

    /**
     * Entrega el PDF tal cual lo cargo el administrador, forzando la descarga.
     *
     * No se genera ni se convierte nada: se sirve el archivo original, que es
     * lo que exige la normativa (formato no modificable).
     */
    public function descargar(TipoDocumento $tipo): StreamedResponse
    {
        $documento = DocumentoLegal::query()
            ->where('tipo', $tipo->value)
            ->firstOrFail();

        abort_unless($documento->estaDisponible(), 404, 'El documento aún no está disponible.');

        $nombreDescarga = $documento->archivo_nombre_original
            ?: Str::slug($documento->titulo).'.pdf';

        return Storage::disk(config('isp.disco_documentos'))->download(
            $documento->archivo_path,
            $nombreDescarga,
            [
                'Content-Type' => 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
