import { Download, FileText } from 'lucide-react';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';
import { Button } from '@/components/ui/button';

type DocumentoLegal = {
    tipo: string;
    titulo: string;
    descripcion: string | null;
    requisito: number;
    disponible: boolean;
    tamano_bytes: number | null;
};

/** Convierte bytes a un texto legible. */
function formatoTamano(bytes: number | null): string | null {
    if (!bytes) {
        return null;
    }

    const mb = bytes / (1024 * 1024);

    return mb >= 1
        ? `${mb.toFixed(1)} MB`
        : `${Math.max(1, Math.round(bytes / 1024))} KB`;
}

/** Requisitos 1 al 8: centro de descargas de los documentos legales en PDF. */
export default function Legal({
    documentos,
}: {
    documentos: DocumentoLegal[];
}) {
    return (
        <>
            <Seo />

            <PageHeader
                titulo="Documentos legales"
                descripcion="Documentación que ponemos a disposición del público. Cada archivo se descarga en PDF, en el formato original en que fue emitido."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                <ul className="space-y-4">
                    {documentos.map((documento) => (
                        <li
                            key={documento.tipo}
                            className="bg-card flex flex-col gap-4 rounded-xl border p-5 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div className="flex min-w-0 gap-4">
                                <FileText
                                    className="text-muted-foreground mt-0.5 size-5 shrink-0"
                                    aria-hidden="true"
                                />
                                <div className="min-w-0">
                                    <h2 className="font-semibold">
                                        {documento.titulo}
                                    </h2>

                                    {documento.descripcion && (
                                        <p className="text-muted-foreground mt-1 text-sm">
                                            {documento.descripcion}
                                        </p>
                                    )}

                                    <p className="text-muted-foreground mt-1 text-xs">
                                        PDF
                                        {formatoTamano(documento.tamano_bytes)
                                            ? ` · ${formatoTamano(documento.tamano_bytes)}`
                                            : ''}
                                    </p>
                                </div>
                            </div>

                            <div className="shrink-0">
                                {documento.disponible ? (
                                    <Button asChild variant="outline">
                                        {/* Descarga directa: enlace normal, no
                                            visita de Inertia. */}
                                        <a
                                            href={`/documentos/${documento.tipo}/descargar`}
                                        >
                                            <Download
                                                className="size-4"
                                                aria-hidden="true"
                                            />
                                            Descargar
                                        </a>
                                    </Button>
                                ) : (
                                    <span className="text-muted-foreground inline-block rounded border border-dashed px-3 py-2 text-sm">
                                        Pendiente de publicación
                                    </span>
                                )}
                            </div>
                        </li>
                    ))}
                </ul>

                {documentos.length === 0 && (
                    <p className="text-muted-foreground italic">
                        No hay documentos publicados todavía.
                    </p>
                )}
            </div>
        </>
    );
}
