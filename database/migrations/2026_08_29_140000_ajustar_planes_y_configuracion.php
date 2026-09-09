<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            // El material comercial de VM MAX solo publica la velocidad de
            // descarga ("hasta 10MB"). Inventar una velocidad de subida seria
            // publicar una caracteristica tecnica no sustentada, asi que el
            // campo pasa a ser opcional y la ficha solo lo muestra si existe.
            $table->unsignedInteger('velocidad_subida')->nullable()->change();
        });

        Schema::table('configuracion', function (Blueprint $table) {
            // Nota que aplica a todos los paquetes (costo de instalacion,
            // equipo en comodato, etc.).
            $table->text('planes_nota')->nullable()->after('empresa_descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion', function (Blueprint $table) {
            $table->dropColumn('planes_nota');
        });

        Schema::table('planes', function (Blueprint $table) {
            $table->unsignedInteger('velocidad_subida')->nullable(false)->change();
        });
    }
};
