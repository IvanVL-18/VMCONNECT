import { Head, usePage } from '@inertiajs/react';
import type { PropsCompartidas } from '@/types/sitio';

/**
 * Mantiene el título del documento al navegar dentro del sitio.
 *
 * Las etiquetas de SEO (descripción, canónica, Open Graph) se generan en el
 * servidor, en `app.blade.php`, a partir de `App\Support\Seo`. Aquí no se
 * repiten: duplicarlas dejaría dos versiones de la misma etiqueta en el <head>
 * después de la primera navegación.
 *
 * Un rastreador siempre pide cada URL por separado, así que recibe la versión
 * del servidor completa; este componente solo cubre la navegación del usuario.
 */
export default function Seo() {
    const { seo } = usePage<PropsCompartidas>().props;

    return <Head title={seo?.titulo ?? undefined} />;
}
