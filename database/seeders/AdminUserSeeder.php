<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crea el usuario administrador inicial. El registro publico esta deshabilitado,
 * asi que esta es la unica via junto con el comando `php artisan isp:crear-admin`.
 *
 * La contrasena se toma de ADMIN_PASSWORD; si no esta definida se genera una
 * aleatoria y se imprime una sola vez en consola.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@vmconnect.test');

        if (User::query()->where('email', $email)->exists()) {
            $this->command?->warn("El administrador {$email} ya existe. No se modificó.");

            return;
        }

        $password = env('ADMIN_PASSWORD') ?: Str::password(16);

        User::query()->create([
            'name' => env('ADMIN_NAME', 'Administrador'),
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->command?->info("Administrador creado: {$email}");

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Contraseña generada (guárdala, no se vuelve a mostrar): {$password}");
        }
    }
}
