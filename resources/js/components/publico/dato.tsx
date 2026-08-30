import { cn } from '@/lib/utils';

/** Un texto sembrado por el seeder que el cliente todavía no ha reemplazado. */
export function esPendiente(valor: string | null | undefined): boolean {
    return typeof valor === 'string' && valor.includes('POR DEFINIR');
}

/**
 * Muestra un valor editable por el administrador.
 *
 * Si el texto sigue siendo un marcador de posición del seeder, se resalta en
 * lugar de mostrarse como si fuera contenido real. Así nadie publica el sitio
 * creyendo que ya está completo.
 */
export function Dato({
    valor,
    vacio = 'Pendiente de captura',
    className,
}: {
    valor: string | null | undefined;
    vacio?: string;
    className?: string;
}) {
    if (!valor) {
        return <span className={cn('text-muted-foreground italic', className)}>{vacio}</span>;
    }

    if (esPendiente(valor)) {
        return (
            <span
                className={cn(
                    'inline-block rounded border border-dashed border-amber-500/60 bg-amber-50 px-2 py-0.5 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200',
                    className,
                )}
            >
                {valor}
            </span>
        );
    }

    return <span className={className}>{valor}</span>;
}

/** Variante en bloque, para textos largos que conservan sus saltos de línea. */
export function DatoBloque({
    valor,
    vacio = 'Pendiente de captura',
    className,
}: {
    valor: string | null | undefined;
    vacio?: string;
    className?: string;
}) {
    if (!valor) {
        return <p className={cn('text-muted-foreground italic', className)}>{vacio}</p>;
    }

    if (esPendiente(valor)) {
        return (
            <p
                className={cn(
                    'rounded border border-dashed border-amber-500/60 bg-amber-50 px-3 py-2 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200',
                    className,
                )}
            >
                {valor}
            </p>
        );
    }

    return <p className={cn('whitespace-pre-line', className)}>{valor}</p>;
}

/** Lista de valores editables (servicios, medios de pago, requisitos...). */
export function ListaDatos({
    valores,
    vacio = 'Pendiente de captura',
    className,
}: {
    valores: string[] | null | undefined;
    vacio?: string;
    className?: string;
}) {
    if (!valores || valores.length === 0) {
        return <p className="text-muted-foreground italic">{vacio}</p>;
    }

    return (
        <ul className={cn('space-y-2', className)}>
            {valores.map((valor, indice) => (
                <li key={`${valor}-${indice}`} className="flex gap-2">
                    <span aria-hidden="true" className="text-muted-foreground mt-0.5">
                        •
                    </span>
                    <Dato valor={valor} />
                </li>
            ))}
        </ul>
    );
}
