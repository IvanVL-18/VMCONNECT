<?php

use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentoLegalController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Publico\LegalController;
use App\Http\Controllers\Publico\PaginasController;
use App\Http\Controllers\Publico\PaquetesController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sitio publico
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');
Route::get('paquetes', PaquetesController::class)->name('paquetes');

Route::controller(PaginasController::class)->group(function () {
    Route::get('contratacion', 'contratacion')->name('contratacion');
    Route::get('quejas', 'quejas')->name('quejas');
    Route::get('medios-de-pago', 'mediosDePago')->name('medios-de-pago');
    Route::get('transparencia', 'transparencia')->name('transparencia');
    Route::get('aviso-de-privacidad', 'avisoDePrivacidad')->name('aviso-de-privacidad');
});

Route::get('legal', [LegalController::class, 'index'])->name('legal');
Route::get('documentos/{tipo}/descargar', [LegalController::class, 'descargar'])->name('documentos.descargar');

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

/*
|--------------------------------------------------------------------------
| Panel de administracion
|--------------------------------------------------------------------------
|
| El login y el logout los aporta el starter kit (Fortify). El registro
| publico esta deshabilitado: los administradores se crean con el seeder o
| con `php artisan isp:crear-admin`.
|
*/

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('planes', PlanController::class)
            ->parameters(['planes' => 'plan']);

        Route::resource('documentos', DocumentoLegalController::class)
            ->parameters(['documentos' => 'documento'])
            ->except(['show']);

        Route::get('configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
        Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
    });

/*
|--------------------------------------------------------------------------
| Compatibilidad con el starter kit
|--------------------------------------------------------------------------
|
| Fortify y las paginas de ajustes redirigen a la ruta `dashboard` despues de
| iniciar sesion, asi que la conservamos apuntando al panel.
|
*/

Route::redirect('dashboard', '/admin')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
