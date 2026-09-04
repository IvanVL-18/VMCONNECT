import { Form, Head, usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast } from 'sonner';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import type { PropsCompartidas } from '@/types/sitio';

type ConfiguracionEditable = {
    marca_comercial: string | null;
    razon_social: string | null;
    domicilio_atencion: string | null;
    horario_oficina: string | null;
    telefono_atencion: string | null;
    correo_atencion: string | null;
    correo_facturacion: string | null;
    facebook_url: string | null;
    instagram_url: string | null;
    whatsapp: string | null;
    servicios_ofrecidos: string[] | null;
    medios_pago: string[] | null;
    medios_pago_nota: string | null;
    contratacion_procedimiento: string | null;
    contratacion_requisitos: string[] | null;
    contratacion_lugar: string | null;
    contratacion_horario: string | null;
    quejas_procedimiento: string | null;
    quejas_domicilio: string | null;
    quejas_telefono: string | null;
    quejas_correo: string | null;
    quejas_horario: string | null;
    quejas_tiempo_promedio: string | null;
    quejas_tiempo_maximo: string | null;
    home_titulo: string | null;
    home_subtitulo: string | null;
    empresa_descripcion: string | null;
    planes_nota: string | null;
    planes_nota_fibra: string | null;
    planes_nota_antena: string | null;
};

type Errores = Record<string, string>;

const claseTextarea =
    'border-input bg-background focus-visible:ring-ring w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:outline-none';

function CampoTexto({
    nombre,
    etiqueta,
    valor,
    ayuda,
    tipo = 'text',
    errores,
}: {
    nombre: string;
    etiqueta: string;
    valor: string | null;
    ayuda?: string;
    tipo?: string;
    errores: Errores;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={nombre}>{etiqueta}</Label>
            <Input id={nombre} name={nombre} type={tipo} defaultValue={valor ?? ''} />
            {ayuda && <p className="text-muted-foreground text-xs">{ayuda}</p>}
            <InputError message={errores[nombre]} />
        </div>
    );
}

function CampoArea({
    nombre,
    etiqueta,
    valor,
    ayuda,
    filas = 4,
    errores,
}: {
    nombre: string;
    etiqueta: string;
    valor: string | null;
    ayuda?: string;
    filas?: number;
    errores: Errores;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={nombre}>{etiqueta}</Label>
            <textarea
                id={nombre}
                name={nombre}
                rows={filas}
                defaultValue={valor ?? ''}
                className={claseTextarea}
            />
            {ayuda && <p className="text-muted-foreground text-xs">{ayuda}</p>}
            <InputError message={errores[nombre]} />
        </div>
    );
}

function CampoLista({
    nombre,
    etiqueta,
    valores,
    errores,
}: {
    nombre: string;
    etiqueta: string;
    valores: string[] | null;
    errores: Errores;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={nombre}>{etiqueta}</Label>
            <textarea
                id={nombre}
                name={nombre}
                rows={4}
                defaultValue={(valores ?? []).join('\n')}
                className={claseTextarea}
            />
            <p className="text-muted-foreground text-xs">Un elemento por línea.</p>
            <InputError message={errores[nombre]} />
        </div>
    );
}

function Seccion({
    titulo,
    descripcion,
    children,
}: {
    titulo: string;
    descripcion?: string;
    children: React.ReactNode;
}) {
    return (
        <section className="bg-card rounded-xl border p-6">
            <h2 className="font-semibold">{titulo}</h2>
            {descripcion && <p className="text-muted-foreground mt-1 text-sm">{descripcion}</p>}
            <div className="mt-5 space-y-5">{children}</div>
        </section>
    );
}

export default function ConfiguracionPage({
    configuracion,
}: {
    configuracion: ConfiguracionEditable;
}) {
    const { flash } = usePage<PropsCompartidas>().props;

    useEffect(() => {
        if (flash?.exito) {
            toast.success(flash.exito);
        }
    }, [flash?.exito]);

    return (
        <>
            <Head title="Configuración del sitio" />

            <div className="flex flex-col gap-6 p-4">
                <div>
                    <h1 className="text-xl font-semibold">Configuración del sitio</h1>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Datos de contacto, textos y contenidos que la normativa obliga a publicar.
                    </p>
                </div>

                <Form
                    action="/admin/configuracion"
                    method="put"
                    className="max-w-3xl space-y-6"
                >
                    {({ processing, errors }) => {
                        const errores = errors as Errores;

                        return (
                            <>
                                <Seccion
                                    titulo="Identidad y contacto"
                                    descripcion="Se muestran en el pie de página de todo el sitio."
                                >
                                    <CampoTexto
                                        nombre="marca_comercial"
                                        etiqueta="Marca comercial"
                                        valor={configuracion.marca_comercial}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="razon_social"
                                        etiqueta="Razón social"
                                        valor={configuracion.razon_social}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="domicilio_atencion"
                                        etiqueta="Domicilio de atención a clientes"
                                        valor={configuracion.domicilio_atencion}
                                        filas={3}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="horario_oficina"
                                        etiqueta="Horario de oficina"
                                        valor={configuracion.horario_oficina}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="telefono_atencion"
                                        etiqueta="Teléfono de atención"
                                        valor={configuracion.telefono_atencion}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="correo_atencion"
                                        etiqueta="Correo de atención a clientes"
                                        tipo="email"
                                        valor={configuracion.correo_atencion}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="correo_facturacion"
                                        etiqueta="Correo de facturación"
                                        tipo="email"
                                        valor={configuracion.correo_facturacion}
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion titulo="Redes sociales">
                                    <CampoTexto
                                        nombre="facebook_url"
                                        etiqueta="URL de Facebook"
                                        tipo="url"
                                        valor={configuracion.facebook_url}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="instagram_url"
                                        etiqueta="URL de Instagram"
                                        tipo="url"
                                        valor={configuracion.instagram_url}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="whatsapp"
                                        etiqueta="WhatsApp"
                                        valor={configuracion.whatsapp}
                                        ayuda="Número con lada, por ejemplo 52155..."
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion
                                    titulo="Servicios ofrecidos"
                                    descripcion="Requisito 10: catálogo de los servicios de telecomunicaciones que ofrece la empresa."
                                >
                                    <CampoLista
                                        nombre="servicios_ofrecidos"
                                        etiqueta="Servicios"
                                        valores={configuracion.servicios_ofrecidos}
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion
                                    titulo="Medios de pago"
                                    descripcion="Requisito 11: formas de pago disponibles."
                                >
                                    <CampoLista
                                        nombre="medios_pago"
                                        etiqueta="Medios de pago"
                                        valores={configuracion.medios_pago}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="medios_pago_nota"
                                        etiqueta="Nota adicional (opcional)"
                                        valor={configuracion.medios_pago_nota}
                                        filas={3}
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion
                                    titulo="Contratación"
                                    descripcion="Requisito 18: procedimiento, requisitos, lugar y horarios de contratación."
                                >
                                    <CampoArea
                                        nombre="contratacion_procedimiento"
                                        etiqueta="Procedimiento de contratación"
                                        valor={configuracion.contratacion_procedimiento}
                                        filas={5}
                                        errores={errores}
                                    />
                                    <CampoLista
                                        nombre="contratacion_requisitos"
                                        etiqueta="Requisitos"
                                        valores={configuracion.contratacion_requisitos}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="contratacion_lugar"
                                        etiqueta="Lugar de contratación"
                                        valor={configuracion.contratacion_lugar}
                                        filas={3}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="contratacion_horario"
                                        etiqueta="Horario de contratación"
                                        valor={configuracion.contratacion_horario}
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion
                                    titulo="Quejas"
                                    descripcion="Requisito 20: canales de atención y tiempos de resolución."
                                >
                                    <CampoArea
                                        nombre="quejas_procedimiento"
                                        etiqueta="Cómo se presenta una queja"
                                        valor={configuracion.quejas_procedimiento}
                                        filas={5}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="quejas_domicilio"
                                        etiqueta="Domicilio para quejas"
                                        valor={configuracion.quejas_domicilio}
                                        filas={3}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="quejas_telefono"
                                        etiqueta="Teléfono de quejas"
                                        valor={configuracion.quejas_telefono}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="quejas_correo"
                                        etiqueta="Correo de quejas"
                                        tipo="email"
                                        valor={configuracion.quejas_correo}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="quejas_horario"
                                        etiqueta="Horario de atención de quejas"
                                        valor={configuracion.quejas_horario}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="quejas_tiempo_promedio"
                                        etiqueta="Tiempo promedio de resolución"
                                        valor={configuracion.quejas_tiempo_promedio}
                                        errores={errores}
                                    />
                                    <CampoTexto
                                        nombre="quejas_tiempo_maximo"
                                        etiqueta="Tiempo máximo de resolución"
                                        valor={configuracion.quejas_tiempo_maximo}
                                        errores={errores}
                                    />
                                </Seccion>

                                <Seccion
                                    titulo="Textos del sitio público"
                                    descripcion="Estos textos aparecen en la página de inicio. Deben ser aprobados por el cliente o el despacho antes de publicarse."
                                >
                                    <CampoTexto
                                        nombre="home_titulo"
                                        etiqueta="Título principal"
                                        valor={configuracion.home_titulo}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="home_subtitulo"
                                        etiqueta="Texto de apoyo"
                                        valor={configuracion.home_subtitulo}
                                        filas={3}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="empresa_descripcion"
                                        etiqueta="Descripción de la empresa"
                                        valor={configuracion.empresa_descripcion}
                                        filas={5}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="planes_nota"
                                        etiqueta="Nota general sobre los paquetes"
                                        valor={configuracion.planes_nota}
                                        ayuda="Aplica a todo el catálogo. Aparece en la portada y al final de /paquetes."
                                        filas={3}
                                        errores={errores}
                                    />
                                    {/* Cada red tiene condiciones de instalación
                                        distintas, así que cada una lleva su
                                        propia nota dentro de su pestaña. */}
                                    <CampoArea
                                        nombre="planes_nota_fibra"
                                        etiqueta="Nota de los paquetes de fibra óptica"
                                        valor={configuracion.planes_nota_fibra}
                                        ayuda="Solo aparece en la pestaña de fibra. Por ejemplo, que no hay costo de instalación."
                                        filas={3}
                                        errores={errores}
                                    />
                                    <CampoArea
                                        nombre="planes_nota_antena"
                                        etiqueta="Nota de los paquetes de antena"
                                        valor={configuracion.planes_nota_antena}
                                        ayuda="Solo aparece en la pestaña de antena. Por ejemplo, el costo de instalación y el equipo en préstamo."
                                        filas={3}
                                        errores={errores}
                                    />
                                </Seccion>

                                <div className="flex items-center gap-3">
                                    <Button type="submit" disabled={processing}>
                                        {processing && <Spinner />}
                                        Guardar configuración
                                    </Button>
                                </div>
                            </>
                        );
                    }}
                </Form>
            </div>
        </>
    );
}

ConfiguracionPage.layout = {
    breadcrumbs: [
        { title: 'Panel', href: '/admin' },
        { title: 'Configuración', href: '/admin/configuracion' },
    ],
};
