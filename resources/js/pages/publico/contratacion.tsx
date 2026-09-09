import { Clock, MapPin } from 'lucide-react';
import { Dato, DatoBloque, ListaDatos } from '@/components/publico/dato';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';

/** Requisito 18: contratación del servicio. */
export default function Contratacion({
    procedimiento,
    requisitos,
    lugar,
    horario,
}: {
    procedimiento: string | null;
    requisitos: string[];
    lugar: string | null;
    horario: string | null;
}) {
    return (
        <>
            <Seo />

            <PageHeader
                titulo="Contratación del servicio"
                descripcion="Aquí se explica cómo contratar el servicio, qué se necesita, dónde se realiza el trámite y en qué horarios."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                <section>
                    <h2 className="text-xl font-semibold">Procedimiento</h2>
                    <div className="mt-3">
                        <DatoBloque
                            valor={procedimiento}
                            vacio="El procedimiento de contratación está pendiente de captura."
                        />
                    </div>
                </section>

                <section className="mt-10">
                    <h2 className="text-xl font-semibold">Requisitos</h2>
                    <div className="mt-3">
                        <ListaDatos
                            valores={requisitos}
                            vacio="Los requisitos de contratación están pendientes de captura."
                        />
                    </div>
                </section>

                <section className="mt-10 grid gap-4 sm:grid-cols-2">
                    <div className="bg-card rounded-xl border p-5">
                        <h2 className="flex items-center gap-2 font-semibold">
                            <MapPin
                                className="text-muted-foreground size-4"
                                aria-hidden="true"
                            />
                            Lugar de contratación
                        </h2>
                        <div className="mt-2 text-sm">
                            <Dato valor={lugar} />
                        </div>
                    </div>

                    <div className="bg-card rounded-xl border p-5">
                        <h2 className="flex items-center gap-2 font-semibold">
                            <Clock
                                className="text-muted-foreground size-4"
                                aria-hidden="true"
                            />
                            Horarios
                        </h2>
                        <div className="mt-2 text-sm">
                            <Dato valor={horario} />
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}
