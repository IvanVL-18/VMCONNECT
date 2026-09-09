import { Clock, Mail, MapPin, Phone, Timer } from 'lucide-react';
import { Dato, DatoBloque } from '@/components/publico/dato';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';

/** Requisito 20: quejas, canales de atención y tiempos de resolución. */
export default function Quejas({
    procedimiento,
    domicilio,
    telefono,
    correo,
    horario,
    tiempoPromedio,
    tiempoMaximo,
}: {
    procedimiento: string | null;
    domicilio: string | null;
    telefono: string | null;
    correo: string | null;
    horario: string | null;
    tiempoPromedio: string | null;
    tiempoMaximo: string | null;
}) {
    const canales = [
        { icono: MapPin, etiqueta: 'Domicilio de atención', valor: domicilio },
        { icono: Phone, etiqueta: 'Teléfono', valor: telefono },
        { icono: Mail, etiqueta: 'Medios electrónicos', valor: correo },
        { icono: Clock, etiqueta: 'Horario de atención', valor: horario },
    ];

    return (
        <>
            <Seo />

            <PageHeader
                titulo="Quejas y atención a usuarios"
                descripcion="Estos son los canales disponibles para presentar una queja o reporte, junto con los tiempos de resolución."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                <section>
                    <h2 className="text-xl font-semibold">
                        Cómo presentar una queja
                    </h2>
                    <div className="mt-3">
                        <DatoBloque
                            valor={procedimiento}
                            vacio="El procedimiento para presentar quejas está pendiente de captura."
                        />
                    </div>
                </section>

                <section className="mt-10">
                    <h2 className="text-xl font-semibold">
                        Canales de atención
                    </h2>
                    <div className="mt-4 grid gap-4 sm:grid-cols-2">
                        {canales.map((canal) => (
                            <div
                                key={canal.etiqueta}
                                className="bg-card rounded-xl border p-5"
                            >
                                <h3 className="text-muted-foreground flex items-center gap-2 text-xs font-semibold tracking-wide uppercase">
                                    <canal.icono
                                        className="size-4"
                                        aria-hidden="true"
                                    />
                                    {canal.etiqueta}
                                </h3>
                                <div className="mt-2 text-sm">
                                    <Dato valor={canal.valor} />
                                </div>
                            </div>
                        ))}
                    </div>
                </section>

                <section className="mt-10">
                    <h2 className="text-xl font-semibold">
                        Tiempos de resolución
                    </h2>
                    <div className="mt-4 grid gap-4 sm:grid-cols-2">
                        <div className="bg-card rounded-xl border p-5">
                            <h3 className="text-muted-foreground flex items-center gap-2 text-xs font-semibold tracking-wide uppercase">
                                <Timer className="size-4" aria-hidden="true" />
                                Tiempo promedio
                            </h3>
                            <div className="mt-2 text-sm">
                                <Dato valor={tiempoPromedio} />
                            </div>
                        </div>

                        <div className="bg-card rounded-xl border p-5">
                            <h3 className="text-muted-foreground flex items-center gap-2 text-xs font-semibold tracking-wide uppercase">
                                <Timer className="size-4" aria-hidden="true" />
                                Tiempo máximo
                            </h3>
                            <div className="mt-2 text-sm">
                                <Dato valor={tiempoMaximo} />
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}
