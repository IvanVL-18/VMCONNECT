import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import SiteFooter from '@/components/publico/site-footer';
import SiteHeader from '@/components/publico/site-header';

/**
 * Envoltura de todas las páginas del sitio público.
 *
 * La clase `sitio-publico` trae la paleta de marca en claro (ver app.css). Se
 * aplica sobre un contenedor y no sobre <html>, así que gana sobre el tema
 * oscuro del panel sin necesidad de coordinar los dos.
 */
export default function PublicoLayout({ children }: { children: React.ReactNode }) {
    const { url } = usePage();

    // El fondo de <html> lo pinta el panel según el tema elegido. Mientras se
    // esté viendo el sitio público lo dejamos en claro, para que al hacer
    // scroll de más no asome una franja oscura detrás del contenido.
    useEffect(() => {
        const raiz = document.documentElement;
        const teniaOscuro = raiz.classList.contains('dark');

        raiz.classList.remove('dark');
        raiz.style.colorScheme = 'light';

        return () => {
            if (teniaOscuro) {
                raiz.classList.add('dark');
                raiz.style.colorScheme = 'dark';
            }
        };
    }, []);

    return (
        <div className="sitio-publico flex min-h-screen flex-col">
            <a
                href="#contenido"
                className="bg-background focus:ring-ring sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:rounded-md focus:border focus:px-4 focus:py-2 focus:ring-2"
            >
                Saltar al contenido
            </a>

            <SiteHeader />

            {/*
                La `key` con la URL hace que React reemplace el contenedor en
                cada navegación, lo que vuelve a disparar la animación de
                entrada. Sin ella, el contenido cambiaría de golpe.
            */}
            <main id="contenido" key={url} className="vm-vista flex-1">
                {children}
            </main>

            <SiteFooter />
        </div>
    );
}
