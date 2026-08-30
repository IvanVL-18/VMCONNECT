/** Encabezado estándar de las páginas interiores del sitio público. */
export default function PageHeader({
    titulo,
    descripcion,
}: {
    titulo: string;
    descripcion?: string;
}) {
    return (
        <div className="relative overflow-hidden border-b">
            <div
                aria-hidden="true"
                className="bg-primary/10 pointer-events-none absolute -top-24 -right-16 size-72 rounded-full blur-3xl"
            />

            <div className="relative mx-auto max-w-6xl px-4 py-12 sm:py-16">
                <h1
                    className="vm-entra text-3xl font-bold tracking-tight text-balance sm:text-4xl"
                    style={{ '--vm-retraso': '0ms' } as React.CSSProperties}
                >
                    {titulo}
                </h1>

                {descripcion && (
                    <p
                        className="text-muted-foreground vm-entra mt-3 max-w-2xl text-base"
                        style={{ '--vm-retraso': '80ms' } as React.CSSProperties}
                    >
                        {descripcion}
                    </p>
                )}
            </div>
        </div>
    );
}
