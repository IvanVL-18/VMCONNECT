<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TipoDocumento;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentoLegalRequest;
use App\Models\DocumentoLegal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Alta y reemplazo de los PDF legales (requisitos 1 al 8).
 *
 * Los archivos se guardan en el disco privado configurado en config/isp.php y
 * se entregan siempre por la ruta de descarga controlada, nunca por URL directa.
 */
class DocumentoLegalController extends Controller
{
    public function index(): Response
    {
        $documentos = DocumentoLegal::query()
            ->get()
            ->sortBy(fn (DocumentoLegal $documento) => $documento->tipo->requisito())
            ->map(fn (DocumentoLegal $documento) => [
                'id' => $documento->id,
                'tipo' => $documento->tipo->value,
                'titulo' => $documento->titulo,
                'requisito' => $documento->tipo->requisito(),
                'activo' => $documento->activo,
                'disponible' => $documento->estaDisponible(),
                'archivo_nombre_original' => $documento->archivo_nombre_original,
                'archivo_bytes' => $documento->archivo_bytes,
                'actualizado' => $documento->updated_at?->translatedFormat('d/m/Y H:i'),
            ])
            ->values()
            ->all();

        return Inertia::render('admin/documentos/index', [
            'documentos' => $documentos,
            'faltantes' => $this->tiposFaltantes(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/documentos/form', [
            'documento' => null,
            'tipos' => $this->tiposFaltantes(),
            'maxKb' => (int) config('isp.max_kb_documento'),
        ]);
    }

    public function store(DocumentoLegalRequest $request): RedirectResponse
    {
        $documento = new DocumentoLegal($request->safe()->except('archivo'));
        $documento->save();

        if ($request->hasFile('archivo')) {
            $this->guardarArchivo($documento, $request->file('archivo'));
        }

        return to_route('admin.documentos.index')
            ->with('exito', 'Documento registrado correctamente.');
    }

    public function edit(DocumentoLegal $documento): Response
    {
        return Inertia::render('admin/documentos/form', [
            'documento' => [
                'id' => $documento->id,
                'tipo' => $documento->tipo->value,
                'titulo' => $documento->titulo,
                'descripcion' => $documento->descripcion,
                'activo' => $documento->activo,
                'disponible' => $documento->estaDisponible(),
                'archivo_nombre_original' => $documento->archivo_nombre_original,
                'archivo_bytes' => $documento->archivo_bytes,
            ],
            'tipos' => TipoDocumento::paraSelect(),
            'maxKb' => (int) config('isp.max_kb_documento'),
        ]);
    }

    public function update(DocumentoLegalRequest $request, DocumentoLegal $documento): RedirectResponse
    {
        $documento->fill($request->safe()->except('archivo'));
        $documento->save();

        if ($request->hasFile('archivo')) {
            $this->guardarArchivo($documento, $request->file('archivo'));
        }

        return to_route('admin.documentos.index')
            ->with('exito', 'Documento actualizado correctamente.');
    }

    public function destroy(DocumentoLegal $documento): RedirectResponse
    {
        $documento->eliminarArchivo();
        $documento->delete();

        return to_route('admin.documentos.index')
            ->with('exito', 'Documento eliminado.');
    }

    /**
     * Guarda el PDF y borra el anterior. El nombre en disco se genera para
     * evitar colisiones; el nombre original se conserva para la descarga.
     */
    private function guardarArchivo(DocumentoLegal $documento, UploadedFile $archivo): void
    {
        $anterior = $documento->archivo_path;

        $ruta = $archivo->store(config('isp.ruta_documentos'), config('isp.disco_documentos'));

        $documento->forceFill([
            'archivo_path' => $ruta,
            'archivo_nombre_original' => $archivo->getClientOriginalName(),
            'archivo_bytes' => $archivo->getSize(),
        ])->save();

        if (filled($anterior) && $anterior !== $ruta) {
            \Illuminate\Support\Facades\Storage::disk(config('isp.disco_documentos'))->delete($anterior);
        }
    }

    /**
     * Tipos de documento que todavia no tienen registro, para el selector de
     * alta (el campo `tipo` es unico).
     *
     * @return array<int, array{value: string, label: string, requisito: int}>
     */
    private function tiposFaltantes(): array
    {
        $existentes = DocumentoLegal::query()->pluck('tipo')->all();

        return array_values(array_filter(
            TipoDocumento::paraSelect(),
            fn (array $tipo) => ! in_array($tipo['value'], array_map(
                fn ($t) => $t instanceof TipoDocumento ? $t->value : $t,
                $existentes
            ), true)
        ));
    }
}
