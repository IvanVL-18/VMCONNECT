<?php

namespace Tests\Feature\Admin;

use App\Models\Configuracion;
use App\Models\User;
use Database\Seeders\ConfiguracionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfiguracionTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visitante_no_edita_la_configuracion(): void
    {
        $this->get(route('admin.configuracion.edit'))->assertRedirect(route('login'));
        $this->put(route('admin.configuracion.update'), [])->assertRedirect(route('login'));
    }

    public function test_el_administrador_guarda_los_datos_de_contacto(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.configuracion.update'), [
                'marca_comercial' => 'Mi ISP',
                'domicilio_atencion' => 'Av. Principal 100',
                'horario_oficina' => 'Lunes a viernes de 9 a 18',
                'telefono_atencion' => '5512345678',
                'correo_atencion' => 'atencion@ejemplo.mx',
                'correo_facturacion' => 'facturacion@ejemplo.mx',
                'servicios_ofrecidos' => "Internet fijo\nTelefonía",
                'medios_pago' => "Efectivo\nTransferencia",
                'contratacion_requisitos' => 'Identificación oficial',
            ])
            ->assertRedirect(route('admin.configuracion.edit'));

        Configuracion::olvidar();
        $configuracion = Configuracion::actual();

        $this->assertSame('Mi ISP', $configuracion->marca_comercial);
        $this->assertSame('atencion@ejemplo.mx', $configuracion->correo_atencion);
        // Los textareas de lista se guardan como arreglo, una línea por elemento.
        $this->assertSame(['Internet fijo', 'Telefonía'], $configuracion->servicios_ofrecidos);
        $this->assertSame(['Efectivo', 'Transferencia'], $configuracion->medios_pago);
        $this->assertSame(['Identificación oficial'], $configuracion->contratacion_requisitos);
    }

    public function test_se_rechaza_un_correo_invalido(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.configuracion.update'), [
                'correo_atencion' => 'esto-no-es-un-correo',
            ])
            ->assertSessionHasErrors('correo_atencion');
    }

    /**
     * El formulario manda todos los campos juntos, asi que un correo con el
     * marcador "[POR DEFINIR: ...]" del seeder rechazaba el guardado completo:
     * no se podia cambiar la marca ni agregar el Facebook.
     */
    public function test_el_seeder_no_deja_datos_que_impidan_guardar(): void
    {
        $this->seed(ConfiguracionSeeder::class);
        Configuracion::olvidar();

        $actual = Configuracion::actual();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.configuracion.update'), [
                ...$actual->only(['correo_atencion', 'correo_facturacion', 'quejas_correo']),
                'marca_comercial' => 'VM MAX',
                'facebook_url' => 'facebook.com/vmmax',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.configuracion.edit'));

        Configuracion::olvidar();
        $configuracion = Configuracion::actual();

        $this->assertSame('VM MAX', $configuracion->marca_comercial);
        // La URL se pega sin esquema y se normaliza al guardar.
        $this->assertSame('https://facebook.com/vmmax', $configuracion->facebook_url);
    }

    /** Solo debe existir una fila de configuración, por más veces que se guarde. */
    public function test_la_configuracion_es_una_sola_fila(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)
            ->put(route('admin.configuracion.update'), ['marca_comercial' => 'Uno']);

        $this->actingAs($usuario)
            ->put(route('admin.configuracion.update'), ['marca_comercial' => 'Dos']);

        $this->assertSame(1, Configuracion::count());
        $this->assertSame('Dos', Configuracion::first()->marca_comercial);
    }
}
