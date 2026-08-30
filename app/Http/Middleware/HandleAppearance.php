<?php

namespace App\Http\Middleware;

use App\Support\Seo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /**
     * Handle an incoming request.
     *
     * El sitio público va siempre en claro: se sirve así desde el servidor para
     * que la primera pintura ya sea la definitiva y no haya un parpadeo oscuro.
     * El panel de administración conserva la preferencia de la persona (claro,
     * oscuro o la del sistema).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apariencia = Seo::esPaginaPublica($request)
            ? 'light'
            : ($request->cookie('appearance') ?? 'system');

        View::share('appearance', $apariencia);

        return $next($request);
    }
}
