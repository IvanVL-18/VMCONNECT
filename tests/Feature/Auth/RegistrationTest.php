<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

/**
 * El registro público está deshabilitado a propósito: el panel es privado y los
 * administradores se crean con el seeder o con `php artisan isp:crear-admin`.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_registro_publico_esta_deshabilitado(): void
    {
        $this->assertFalse(Features::enabled(Features::registration()));
    }

    public function test_no_existe_pantalla_de_registro(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_no_se_puede_crear_una_cuenta_por_http(): void
    {
        $this->post('/register', [
            'name' => 'Intruso',
            'email' => 'intruso@ejemplo.mx',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertGuest();
        $this->assertSame(0, User::count());
    }
}
