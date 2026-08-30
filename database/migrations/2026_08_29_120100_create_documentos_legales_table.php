<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_legales', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->unique()->comment('App\Enums\TipoDocumento');
            $table->string('titulo');
            $table->string('archivo_path')->nullable()->comment('Ruta en el disco configurado en config/isp.php');
            $table->string('archivo_nombre_original')->nullable();
            $table->unsignedBigInteger('archivo_bytes')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_legales');
    }
};
