import { Form, Head, Link } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

type DocumentoEditable = {
    id: number;
    tipo: string;
    titulo: string;
    descripcion: string | null;
    activo: boolean;
    disponible: boolean;
    archivo_nombre_original: string | null;
    archivo_bytes: number | null;
};

type TipoOpcion = {
    value: string;
    label: string;
    requisito: number;
};

export default function DocumentoForm({
    documento,
    tipos,
    maxKb,
}: {
    documento: DocumentoEditable | null;
    tipos: TipoOpcion[];
    maxKb: number;
}) {
    const editando = documento !== null;
    const maxMb = Math.floor(maxKb / 1024);

    return (
        <>
            <Head title={editando ? `Editar ${documento.titulo}` : 'Registrar documento'} />

            <div className="flex flex-col gap-6 p-4">
                <div>
                    <h1 className="text-xl font-semibold">
                        {editando ? `Editar «${documento.titulo}»` : 'Registrar documento'}
                    </h1>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Solo se aceptan archivos PDF de hasta {maxMb} MB. El archivo se publica tal
                        como se sube, sin conversiones.
                    </p>
                </div>

                <Form
                    action={editando ? `/admin/documentos/${documento.id}` : '/admin/documentos'}
                    method="post"
                    encType="multipart/form-data"
                    className="max-w-2xl space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            {/* Con archivos adjuntos la petición debe viajar como
                                POST; Laravel la interpreta como PUT gracias a
                                este campo. */}
                            {editando && <input type="hidden" name="_method" value="put" />}

                            <div className="grid gap-2">
                                <Label htmlFor="tipo">Tipo de documento</Label>
                                <select
                                    id="tipo"
                                    name="tipo"
                                    required
                                    defaultValue={documento?.tipo ?? ''}
                                    className="border-input bg-background focus-visible:ring-ring w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:outline-none"
                                >
                                    <option value="" disabled>
                                        Selecciona un tipo
                                    </option>
                                    {tipos.map((tipo) => (
                                        <option key={tipo.value} value={tipo.value}>
                                            {tipo.requisito}. {tipo.label}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.tipo} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="titulo">Título</Label>
                                <Input
                                    id="titulo"
                                    name="titulo"
                                    required
                                    defaultValue={documento?.titulo ?? ''}
                                />
                                <InputError message={errors.titulo} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="descripcion">Descripción (opcional)</Label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows={3}
                                    defaultValue={documento?.descripcion ?? ''}
                                    className="border-input bg-background focus-visible:ring-ring w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:outline-none"
                                />
                                <InputError message={errors.descripcion} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="archivo">
                                    Archivo PDF {editando && documento.disponible ? '(reemplazar)' : ''}
                                </Label>
                                <Input
                                    id="archivo"
                                    name="archivo"
                                    type="file"
                                    accept="application/pdf,.pdf"
                                />

                                {editando && documento.archivo_nombre_original && (
                                    <p className="text-muted-foreground text-xs">
                                        Archivo actual: {documento.archivo_nombre_original}. Si no
                                        eliges uno nuevo, se conserva.
                                    </p>
                                )}

                                <InputError message={errors.archivo} />
                            </div>

                            <div className="flex items-center gap-3">
                                <Checkbox
                                    id="activo"
                                    name="activo"
                                    value="1"
                                    defaultChecked={documento?.activo ?? true}
                                />
                                <Label htmlFor="activo">Visible en el sitio público</Label>
                            </div>

                            <div className="flex items-center gap-3">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />}
                                    {editando ? 'Guardar cambios' : 'Registrar documento'}
                                </Button>
                                <Button asChild variant="outline" type="button">
                                    <Link href="/admin/documentos">Cancelar</Link>
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

DocumentoForm.layout = {
    breadcrumbs: [
        { title: 'Panel', href: '/admin' },
        { title: 'Documentos legales', href: '/admin/documentos' },
    ],
};
