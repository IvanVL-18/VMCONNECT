<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Crea (o actualiza la contrasena de) un administrador.
 *
 * El registro publico esta deshabilitado a proposito, asi que este comando y el
 * AdminUserSeeder son las unicas formas de dar de alta a alguien en el panel.
 */
class CrearAdministrador extends Command
{
    protected $signature = 'isp:crear-admin
                            {--nombre= : Nombre del administrador}
                            {--email= : Correo con el que iniciará sesión}
                            {--password= : Contraseña; si se omite se genera una aleatoria}
                            {--forzar : Si el correo ya existe, actualiza su contraseña}';

    protected $description = 'Crea un usuario administrador para el panel (el registro público está deshabilitado)';

    public function handle(): int
    {
        $nombre = $this->option('nombre') ?: $this->ask('Nombre del administrador');
        $email = $this->option('email') ?: $this->ask('Correo electrónico');

        $generada = false;
        $password = $this->option('password');

        if (blank($password)) {
            $password = Str::password(16);
            $generada = true;
        }

        try {
            Validator::make(
                ['name' => $nombre, 'email' => $email, 'password' => $password],
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'password' => ['required', 'string', Password::default()],
                ]
            )->validate();
        } catch (ValidationException $e) {
            foreach ($e->validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $existente = User::query()->where('email', $email)->first();

        if ($existente && ! $this->option('forzar')) {
            $this->error("Ya existe un usuario con el correo {$email}. Usa --forzar para cambiarle la contraseña.");

            return self::FAILURE;
        }

        if ($existente) {
            $existente->update(['name' => $nombre, 'password' => Hash::make($password)]);
            $this->info("Contraseña actualizada para {$email}.");
        } else {
            User::query()->create([
                'name' => $nombre,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->info("Administrador creado: {$email}");
        }

        if ($generada) {
            $this->warn("Contraseña generada (guárdala, no se vuelve a mostrar): {$password}");
        }

        return self::SUCCESS;
    }
}
