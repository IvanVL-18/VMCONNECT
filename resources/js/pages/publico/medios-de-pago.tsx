import { usePage } from '@inertiajs/react';
import { Banknote, MessageCircle } from 'lucide-react';
import { Dato, DatoBloque } from '@/components/publico/dato';
import PageHeader from '@/components/publico/page-header';
import Seo from '@/components/publico/seo';
import { Button } from '@/components/ui/button';
import { enlaceWhatsapp } from '@/lib/whatsapp';
import type { PropsCompartidas } from '@/types/sitio';

/**
 * Requisito 11: medios de pago disponibles.
 *
 * La empresa no acepta tarjeta de crédito, débito ni Visa en línea, así que la
 * página no las ofrece. El pago se gestiona por WhatsApp con el área de
 * atención: ese es el canal que la empresa opera de verdad.
 */
export default function MediosDePago({
    medios,
    nota,
}: {
    medios: string[];
    nota: string | null;
}) {
    const { sitio } = usePage<PropsCompartidas>().props;

    const whatsapp = enlaceWhatsapp(
        sitio?.whatsapp,
        'Hola, quiero información para realizar el pago de mi servicio de Internet.',
    );

    return (
        <>
            <Seo />

            <PageHeader
                titulo="Medios de pago"
                descripcion="Estas son las formas de pago disponibles para cubrir el servicio."
            />

            <div className="mx-auto max-w-4xl px-4 py-12">
                {/* El pago se coordina por WhatsApp: es el canal principal y por
                    eso encabeza la página. */}
                {whatsapp && (
                    <section className="bg-card rounded-2xl border p-6 sm:p-8">
                        <div className="flex flex-wrap items-center justify-between gap-6">
                            <div className="min-w-64 flex-1">
                                <h2 className="text-xl font-semibold">
                                    Realiza tu pago por WhatsApp
                                </h2>
                                <p className="text-muted-foreground mt-2 text-sm">
                                    Escríbenos y el área de atención a clientes
                                    te indica cómo cubrir tu mensualidad y
                                    recibe tu comprobante.
                                </p>
                                {sitio?.whatsapp && (
                                    <p className="text-muted-foreground mt-2 text-sm">
                                        WhatsApp:{' '}
                                        <Dato valor={sitio.whatsapp} />
                                    </p>
                                )}
                            </div>

                            <Button
                                asChild
                                size="lg"
                                className="rounded-full px-6"
                            >
                                <a
                                    href={whatsapp}
                                    target="_blank"
                                    rel="noreferrer noopener"
                                >
                                    <MessageCircle
                                        className="size-5"
                                        aria-hidden="true"
                                    />
                                    Abrir WhatsApp
                                </a>
                            </Button>
                        </div>
                    </section>
                )}

                <section className={whatsapp ? 'mt-10' : ''}>
                    <h2 className="text-xl font-semibold">
                        Formas de pago aceptadas
                    </h2>

                    {medios.length === 0 ? (
                        <p className="text-muted-foreground mt-4 italic">
                            Los medios de pago están pendientes de captura.
                        </p>
                    ) : (
                        <ul className="mt-4 grid gap-4 sm:grid-cols-2">
                            {medios.map((medio, indice) => (
                                <li
                                    key={`${medio}-${indice}`}
                                    className="bg-card flex items-start gap-3 rounded-xl border p-5"
                                >
                                    <Banknote
                                        className="text-muted-foreground mt-0.5 size-5 shrink-0"
                                        aria-hidden="true"
                                    />
                                    <Dato valor={medio} />
                                </li>
                            ))}
                        </ul>
                    )}
                </section>

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
                    Este sitio no procesa pagos en línea ni solicita datos de
                    tarjeta. Los pagos se realizan por los medios señalados
                    arriba.
                </p>
            </div>
        </>
    );
}
