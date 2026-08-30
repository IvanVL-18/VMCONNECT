<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();

            // Identidad y datos de contacto (obligatorios en el pie de pagina)
            $table->string('marca_comercial')->nullable();
            $table->string('razon_social')->nullable();
            $table->text('domicilio_atencion')->nullable();
            $table->string('horario_oficina')->nullable();
            $table->string('telefono_atencion')->nullable();
            $table->string('correo_atencion')->nullable();
            $table->string('correo_facturacion')->nullable();

            // Redes sociales
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('whatsapp')->nullable();

            // Requisito 10: catalogo de tramites / servicios ofrecidos
            $table->json('servicios_ofrecidos')->nullable();

            // Requisito 11: medios de pago
            $table->json('medios_pago')->nullable();
            $table->text('medios_pago_nota')->nullable();

            // Requisito 18: contratacion
            $table->text('contratacion_procedimiento')->nullable();
            $table->json('contratacion_requisitos')->nullable();
            $table->text('contratacion_lugar')->nullable();
            $table->string('contratacion_horario')->nullable();

            // Requisito 20: quejas
            $table->text('quejas_procedimiento')->nullable();
            $table->text('quejas_domicilio')->nullable();
            $table->string('quejas_telefono')->nullable();
            $table->string('quejas_correo')->nullable();
            $table->string('quejas_horario')->nullable();
            $table->string('quejas_tiempo_promedio')->nullable();
            $table->string('quejas_tiempo_maximo')->nullable();

            // Textos editables del sitio publico (para que nadie invente copy)
            $table->string('home_titulo')->nullable();
            $table->text('home_subtitulo')->nullable();
            $table->text('empresa_descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
