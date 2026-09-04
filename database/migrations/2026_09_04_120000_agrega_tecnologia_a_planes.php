<?php

use App\Enums\Tecnologia;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            // VM Connect entrega el servicio por dos redes distintas y los
            // nombres comerciales se repiten entre ambas, asi que la tecnologia
            // forma parte de la identidad del paquete.
            $table->string('tecnologia', 20)
                ->default(Tecnologia::Antena->value)
                ->after('nombre');

            // La ficha marca con un icono los paquetes que incluyen television
            // o camara de seguridad, tal como lo hace el material comercial.
            $table->boolean('incluye_tv')->default(false)->after('caracteristicas');
            $table->boolean('incluye_camara')->default(false)->after('incluye_tv');

            $table->index(['tecnologia', 'activo', 'orden']);
        });

        // Los paquetes que ya estaban capturados son los de antena.
        DB::table('planes')->update(['tecnologia' => Tecnologia::Antena->value]);

        Schema::table('configuracion', function (Blueprint $table) {
            // Cada red tiene su propia condicion de instalacion (la fibra no
            // cobra instalacion; la antena si), asi que la nota unica ya no
            // alcanza: se necesita una por tecnologia. `planes_nota` se queda
            // como la nota general que aplica a todo el catalogo.
            $table->text('planes_nota_fibra')->nullable()->after('planes_nota');
            $table->text('planes_nota_antena')->nullable()->after('planes_nota_fibra');
        });
    }

    public function down(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropIndex(['tecnologia', 'activo', 'orden']);
            $table->dropColumn(['tecnologia', 'incluye_tv', 'incluye_camara']);
        });

        Schema::table('configuracion', function (Blueprint $table) {
            $table->dropColumn(['planes_nota_fibra', 'planes_nota_antena']);
        });
    }
};
