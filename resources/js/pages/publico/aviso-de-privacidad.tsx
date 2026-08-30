import { Download } from 'lucide-react';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';
import { Button } from '@/components/ui/button';

type Documento = {
    titulo: string;
    descripcion: string | null;
    disponible: boolean;
    tipo: string;
};

/** Requisito 6: aviso de privacidad como página y como PDF descargable. */
export default function AvisoDePrivacidad({ documento }: { documento: Documento | null }) {
    return (
        <>
            <Seo />

            <PageHeader
                titulo="Aviso de privacidad"
                descripcion="Documento que informa cómo se tratan los datos personales de nuestros usuarios."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                {documento ? (
                    <div className="bg-card rounded-xl border p-6">
                        <h2 className="text-lg font-semibold">{documento.titulo}</h2>

                        {documento.descripcion && (
                            <p className="text-muted-foreground mt-2 whitespace-pre-line">
                                {documento.descripcion}
                            </p>
                        )}

                        <div className="mt-6">
                            {documento.disponible ? (
                                <Button asChild>
                                    <a href={`/documentos/${documento.tipo}/descargar`}>
                                        <Download className="size-4" aria-hidden="true" />
                                        Descargar el aviso de privacidad (PDF)
                                    </a>
                                </Button>
                            ) : (
                                <p className="inline-block rounded border border-dashed border-amber-500/60 bg-amber-50 px-3 py-2 text-sm text-amber-800 dark:bg-amber-950/40 dark:text-amber-200">
                                    El PDF del aviso de privacidad está pendiente de publicación.
                                </p>
                            )}
                        </div>
                    </div>
                ) : (
                    <p className="text-muted-foreground italic">
                        El aviso de privacidad todavía no está registrado.
                    </p>
                )}
            </div>
        </>
    );
}
