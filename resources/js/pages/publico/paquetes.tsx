import { ExternalLink, Info } from 'lucide-react';
import { DatoBloque } from '@/components/publico/dato';
import PageHeader from '@/components/publico/page-header';
import PlanCard from '@/components/publico/plan-card';
import Seo from '@/components/publico/seo';
import type { PlanPublico } from '@/types/sitio';

/**
 * Requisitos 12 y 13: precios y tarifas de cada paquete, con características,
 * restricciones y el folio de la tarifa inscrita en el IFT.
 */
export default function Paquetes({
    planes,
    nota,
    enlaceVisorTarifas,
}: {
    planes: PlanPublico[];
    nota: string | null;
    enlaceVisorTarifas: string | null;
}) {
    return (
        <>
            <Seo />

            <PageHeader
                titulo="Paquetes y tarifas"
                descripcion="Detalle de cada paquete: precio mensual, velocidad contratada, características, restricciones aplicables y el folio de la tarifa inscrita ante el IFT."
            />

            <div className="mx-auto max-w-6xl px-4 py-12">
                {planes.length === 0 ? (
                    <p className="text-muted-foreground">Todavía no hay paquetes publicados.</p>
                ) : (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {planes.map((plan, indice) => (
                            <PlanCard key={plan.id} plan={plan} indice={indice} detallado />
                        ))}
                    </div>
                )}

                {nota && (
                    <div className="bg-accent/50 mt-8 flex gap-3 rounded-2xl border p-5">
                        <Info
                            className="text-accent-foreground/70 mt-0.5 size-5 shrink-0"
                            aria-hidden="true"
                        />
                        <div className="text-sm">
                            <DatoBloque valor={nota} />
                        </div>
                    </div>
                )}

                {enlaceVisorTarifas && (
                    <div className="bg-muted/50 mt-6 rounded-2xl border p-6">
                        <h2 className="font-semibold">Consulta pública de tarifas</h2>
                        <p className="text-muted-foreground mt-2 text-sm">
                            Las tarifas registradas pueden consultarse directamente en el visor del
                            Instituto Federal de Telecomunicaciones.
                        </p>
                        <a
                            href={enlaceVisorTarifas}
                            target="_blank"
                            rel="noreferrer noopener"
                            className="text-primary mt-3 inline-flex items-center gap-1.5 text-sm font-medium underline underline-offset-4"
                        >
                            Abrir el visor de tarifas del IFT
                            <ExternalLink className="size-3.5" aria-hidden="true" />
                        </a>
                    </div>
                )}
            </div>
        </>
    );
}
