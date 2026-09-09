import { Head, Link } from '@inertiajs/react';
import { AlertTriangle, FileText, Package, Settings } from 'lucide-react';
import { Button } from '@/components/ui/button';

type Resumen = {
    planes_totales: number;
    planes_activos: number;
    documentos_totales: number;
    documentos_cargados: number;
};

type Pendiente = {
    tipo: string;
    detalle: string;
};

export default function AdminDashboard({
    resumen,
    pendientes,
}: {
    resumen: Resumen;
    pendientes: Pendiente[];
}) {
    const tarjetas = [
        {
            titulo: 'Paquetes activos',
            valor: `${resumen.planes_activos} de ${resumen.planes_totales}`,
            icono: Package,
            href: '/admin/planes',
            accion: 'Administrar paquetes',
        },
        {
            titulo: 'Documentos cargados',
            valor: `${resumen.documentos_cargados} de ${resumen.documentos_totales}`,
            icono: FileText,
            href: '/admin/documentos',
            accion: 'Administrar documentos',
        },
    ];

    return (
        <>
            <Head title="Panel" />

            <div className="flex flex-col gap-6 p-4">
                <div className="grid gap-4 sm:grid-cols-2">
                    {tarjetas.map((tarjeta) => (
                        <div
                            key={tarjeta.titulo}
                            className="bg-card rounded-xl border p-6"
                        >
                            <div className="flex items-start justify-between gap-4">
                                <div>
                                    <h2 className="text-muted-foreground text-sm font-medium">
                                        {tarjeta.titulo}
                                    </h2>
                                    <p className="mt-2 text-2xl font-bold">
                                        {tarjeta.valor}
                                    </p>
                                </div>
                                <tarjeta.icono
                                    className="text-muted-foreground size-5 shrink-0"
                                    aria-hidden="true"
                                />
                            </div>
                            <Button
                                asChild
                                variant="outline"
                                size="sm"
                                className="mt-4"
                            >
                                <Link href={tarjeta.href}>
                                    {tarjeta.accion}
                                </Link>
                            </Button>
                        </div>
                    ))}
                </div>

                <div className="bg-card rounded-xl border p-6">
                    <h2 className="flex items-center gap-2 font-semibold">
                        <AlertTriangle
                            className="size-4 text-amber-600 dark:text-amber-400"
                            aria-hidden="true"
                        />
                        Pendientes para cumplir con la normativa
                    </h2>

                    {pendientes.length === 0 ? (
                        <p className="text-muted-foreground mt-3 text-sm">
                            No hay pendientes: todos los documentos están
                            cargados y la información está capturada.
                        </p>
                    ) : (
                        <ul className="mt-4 space-y-2">
                            {pendientes.map((pendiente, indice) => (
                                <li
                                    key={indice}
                                    className="flex flex-col gap-1 border-b pb-2 text-sm last:border-b-0 sm:flex-row sm:items-baseline sm:gap-3"
                                >
                                    <span className="text-muted-foreground shrink-0 text-xs font-semibold tracking-wide uppercase">
                                        {pendiente.tipo}
                                    </span>
                                    <span>{pendiente.detalle}</span>
                                </li>
                            ))}
                        </ul>
                    )}

                    <Button
                        asChild
                        variant="outline"
                        size="sm"
                        className="mt-4"
                    >
                        <Link href="/admin/configuracion">
                            <Settings className="size-4" aria-hidden="true" />
                            Editar configuración del sitio
                        </Link>
                    </Button>
                </div>
            </div>
        </>
    );
}

AdminDashboard.layout = {
    breadcrumbs: [{ title: 'Panel', href: '/admin' }],
};
