/**
 * Construye la liga de WhatsApp a partir de un número capturado en el panel.
 *
 * wa.me exige el número en formato internacional y sin separadores, así que se
 * limpia todo lo que no sea dígito. Devuelve null cuando no hay número: quien
 * lo use debe ocultar el botón en lugar de generar una liga rota.
 */
export function enlaceWhatsapp(numero: string | null | undefined, mensaje?: string): string | null {
    const digitos = (numero ?? '').replace(/\D/g, '');

    if (digitos === '') {
        return null;
    }

    const base = `https://wa.me/${digitos}`;

    return mensaje ? `${base}?text=${encodeURIComponent(mensaje)}` : base;
}
