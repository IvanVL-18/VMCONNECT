import { CreditCard } from 'lucide-react';
import { Dato, DatoBloque } from '@/components/publico/dato';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';

/** Requisito 11: medios de pago disponibles. */
export default function MediosDePago({
    medios,
    nota,
}: {
    medios: string[];
    nota: string | null;
}) {
    return (
        <>
            <Seo />

            <PageHeader
                titulo="Medios de pago"
                descripcion="Estas son las formas de pago disponibles para cubrir el servicio."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                {medios.length === 0 ? (
                    <p className="text-muted-foreground italic">
                        Los medios de pago están pendientes de captura.
                    </p>
                ) : (
                    <ul className="grid gap-4 sm:grid-cols-2">
                        {medios.map((medio, indice) => (
                            <li
                                key={`${medio}-${indice}`}
                                className="bg-card flex items-start gap-3 rounded-xl border p-5"
                            >
                                <CreditCard
                                    className="text-muted-foreground mt-0.5 size-5 shrink-0"
                                    aria-hidden="true"
                                />
                                <Dato valor={medio} />
                            </li>
                        ))}
                    </ul>
                )}

                {nota && (
                    <div className="bg-muted/40 mt-8 rounded-xl border p-5">
                        <h2 className="font-semibold">Información adicional</h2>
                        <div className="text-muted-foreground mt-2 text-sm">
                            <DatoBloque valor={nota} />
                        </div>
                    </div>
                )}

                {/* El sitio no procesa cobros: solo informa los medios de pago. */}
                <p className="text-muted-foreground mt-8 text-sm">
                    Este sitio no procesa pagos en línea. Los pagos se realizan por los medios
                    señalados arriba.
                </p>
            </div>
        </>
    );
}
