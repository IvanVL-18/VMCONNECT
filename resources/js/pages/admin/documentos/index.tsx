import { Head, Link, router, usePage } from '@inertiajs/react';
import { CheckCircle2, CircleDashed, Download, Pencil, Plus, Trash2 } from 'lucide-react';
import { useEffect } from 'react';
import { toast } from 'sonner';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { PropsCompartidas } from '@/types/sitio';

type DocumentoFila = {
    id: number;
    tipo: string;
    titulo: string;
    requisito: number;
    activo: boolean;
    disponible: boolean;
    archivo_nombre_original: string | null;
    archivo_bytes: number | null;
    actualizado: string | null;
};

type TipoFaltante = {
    value: string;
    label: string;
    requisito: number;
};

export default function DocumentosIndex({
    documentos,
    faltantes,
}: {
    documentos: DocumentoFila[];
    faltantes: TipoFaltante[];
}) {
    const { flash } = usePage<PropsCompartidas>().props;

    useEffect(() => {
        if (flash?.exito) {
            toast.success(flash.exito);
        }
    }, [flash?.exito]);

    const eliminar = (documento: DocumentoFila) => {
        if (!window.confirm(`¿Eliminar «${documento.titulo}» y su archivo? Esta acción no se puede deshacer.`)) {
            return;
        }

        router.delete(`/admin/documentos/${documento.id}`, { preserveScroll: true });
    };

    return (
        <>
            <Head title="Documentos legales" />

            <div className="flex flex-col gap-6 p-4">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 className="text-xl font-semibold">Documentos legales</h1>
                        <p className="text-muted-foreground mt-1 text-sm">
                            Sube o reemplaza los PDF que entrega el despacho. Se publican tal cual,
                            sin modificarlos.
                        </p>
                    </div>

                    {faltantes.length > 0 && (
                        <Button asChild>
                            <Link href="/admin/documentos/create">
                                <Plus className="size-4" aria-hidden="true" />
                                Registrar documento
                            </Link>
                        </Button>
                    )}
                </div>

                <div className="overflow-x-auto rounded-xl border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50">
                            <tr>
                                <th scope="col" className="px-4 py-3 text-left font-semibold">Req.</th>
                                <th scope="col" className="px-4 py-3 text-left font-semibold">Documento</th>
                                <th scope="col" className="px-4 py-3 text-left font-semibold">Archivo</th>
                                <th scope="col" className="px-4 py-3 text-left font-semibold">Estado</th>
                                <th scope="col" className="px-4 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {documentos.map((documento) => (
                                <tr key={documento.id} className="border-t">
                                    <td className="text-muted-foreground px-4 py-3">
                                        {documento.requisito}
                                    </td>
                                    <td className="px-4 py-3 font-medium">
                                        {documento.titulo}
                                        {!documento.activo && (
                                            <Badge variant="outline" className="ml-2">
                                                Oculto
                                            </Badge>
                                        )}
                                    </td>
                                    <td className="text-muted-foreground max-w-56 truncate px-4 py-3">
                                        {documento.archivo_nombre_original ?? '—'}
                                    </td>
                                    <td className="px-4 py-3">
                                        {documento.disponible ? (
                                            <span className="inline-flex items-center gap-1.5 text-green-700 dark:text-green-400">
                                                <CheckCircle2 className="size-4" aria-hidden="true" />
                                                Publicado
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center gap-1.5 text-amber-700 dark:text-amber-400">
                                                <CircleDashed className="size-4" aria-hidden="true" />
                                                Falta el PDF
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex justify-end gap-2">
                                            {documento.disponible && (
                                                <Button asChild variant="outline" size="sm">
                                                    <a href={`/documentos/${documento.tipo}/descargar`}>
                                                        <Download className="size-3.5" aria-hidden="true" />
                                                        <span className="sr-only">Descargar</span>
                                                    </a>
                                                </Button>
                                            )}
                                            <Button asChild variant="outline" size="sm">
                                                <Link href={`/admin/documentos/${documento.id}/edit`}>
                                                    <Pencil className="size-3.5" aria-hidden="true" />
                                                    <span className="sr-only sm:not-sr-only">Editar</span>
                                                </Link>
                                            </Button>
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                onClick={() => eliminar(documento)}
                                            >
                                                <Trash2 className="size-3.5" aria-hidden="true" />
                                                <span className="sr-only">Eliminar</span>
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

DocumentosIndex.layout = {
    breadcrumbs: [
        { title: 'Panel', href: '/admin' },
        { title: 'Documentos legales', href: '/admin/documentos' },
    ],
};
