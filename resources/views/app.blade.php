<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
        {{--
            SEO renderizado en el servidor.

            El slot de <x-inertia::head> solo se usa cuando NO corre el proceso
            de SSR de Inertia; si algún día se activa, el head del SSR gana y
            este bloque deja de emitirse. Se genera aquí para que un rastreador
            que no ejecute JavaScript reciba título, descripción y Open Graph ya
            en la respuesta HTML.
        --}}
        <x-inertia::head>
            @php($seo = $page['props']['seo'] ?? [])
            @php($nombreApp = config('app.name', 'Laravel'))

            <title>{{ ($seo['titulo'] ?? null) ? $seo['titulo'].' - '.$nombreApp : $nombreApp }}</title>

            @if (! empty($seo['descripcion']))
                <meta name="description" content="{{ $seo['descripcion'] }}">
            @endif

            @if (! empty($seo['canonica']))
                <link rel="canonical" href="{{ $seo['canonica'] }}">
            @endif

            {{-- El panel de administración no debe aparecer en buscadores. --}}
            @if (empty($seo['indexable']))
                <meta name="robots" content="noindex, nofollow">
            @endif

            <meta property="og:type" content="website">
            <meta property="og:locale" content="es_MX">
            <meta property="og:site_name" content="{{ $nombreApp }}">
            <meta property="og:title" content="{{ $seo['titulo'] ?? $nombreApp }}">
            @if (! empty($seo['descripcion']))
                <meta property="og:description" content="{{ $seo['descripcion'] }}">
                <meta name="twitter:description" content="{{ $seo['descripcion'] }}">
            @endif
            @if (! empty($seo['canonica']))
                <meta property="og:url" content="{{ $seo['canonica'] }}">
            @endif
            <meta name="twitter:card" content="summary">
            <meta name="twitter:title" content="{{ $seo['titulo'] ?? $nombreApp }}">
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
