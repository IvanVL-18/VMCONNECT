# VM Connect Web

Sitio publico + panel administrativo de un ISP. Laravel 13 (PHP 8.3) con Inertia 3 + React 19
y Tailwind 4. Se sirve con Herd en Windows.

## Comandos

| Tarea | Comando |
| --- | --- |
| Dev (server + queue + vite) | `composer dev` |
| Solo frontend | `npm run dev` |
| Build | `npm run build` |
| Suite completa (lint + phpstan + tests) | `composer test` |
| Solo tests | `php artisan test` |
| Un test | `php artisan test --filter=NombreDelTest` |
| Formato PHP | `composer lint` (Pint) |
| Analisis estatico | `composer types:check` (PHPStan/Larastan) |
| Lint/format JS+TS | `npm run check` / `npm run check:fix` |
| Tipos TS | `npm run types:check` |
| Igual que CI | `composer ci:check` |

CI (`.github/workflows/tests.yml`) corre el equivalente a `composer ci:check`, asi que eso es lo
que debe pasar antes de dar por terminado un cambio.

## Estructura

- `routes/web.php` — sitio publico (`/`, `paquetes`, paginas legales, `sitemap.xml`) y panel bajo
  `/admin` (middleware `auth` + `verified`, nombres `admin.*`). `routes/settings.php` trae las
  pantallas de perfil/seguridad del starter kit.
- `app/Http/Controllers/Publico` y `.../Admin` — un controlador por area; varios son invocables
  (`__invoke`).
- `app/Models` — `Plan`, `DocumentoLegal`, `Configuracion`, `User`.
- `app/Support/Seo.php`, `app/Enums/TipoDocumento.php`.
- `resources/js/pages/{publico,admin,auth,settings}` — paginas Inertia (React + TypeScript).
  `components/`, `layouts/`, `hooks/`, `lib/` acompañan.
- `resources/views/` solo tiene `app.blade.php` (shell de Inertia) y `sitemap.blade.php`.
- `tests/Feature/{Publico,Admin,Auth,Settings}` — PHPUnit 12.

## Convenciones

- El dominio esta en español (rutas, modelos, comentarios). Mantener ese idioma al agregar codigo;
  los helpers genericos del starter kit siguen en ingles.
- No hay registro publico: los administradores se crean con el seeder o con
  `php artisan isp:crear-admin`.
- Wayfinder genera `resources/js/{actions,routes,wayfinder}`; son artefactos, no se editan a mano
  (estan en `.gitignore`).
- Formato PHP con Pint (`pint.json`), no a mano. PHPStan segun `phpstan.neon`.
