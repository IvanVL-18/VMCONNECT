<?php

namespace App\Enums;

/**
 * Tecnologia con la que se entrega el servicio de acceso a Internet.
 *
 * VM MAX opera dos redes distintas y cada una tiene su propio catalogo de
 * paquetes, sus velocidades y sus condiciones de instalacion. Los nombres
 * comerciales se repiten entre ambas ("Básico", "Estándar", "Premium",
 * "Platino"), asi que la tecnologia es lo unico que distingue un paquete de
 * otro: sin ella el sitio mostraria dos "Básico" al mismo precio y con
 * velocidades diferentes.
 */
enum Tecnologia: string
{
    case Fibra = 'fibra';
    case Antena = 'antena';

    public function etiqueta(): string
    {
        return match ($this) {
            self::Fibra => 'Fibra óptica',
            self::Antena => 'Antena',
        };
    }

    /** Nombre completo del servicio, para titulos y textos de apoyo. */
    public function titulo(): string
    {
        return match ($this) {
            self::Fibra => 'Internet por fibra óptica',
            self::Antena => 'Internet por antena',
        };
    }

    /** @return array<int, array{value: string, label: string}> */
    public static function paraSelect(): array
    {
        return array_map(fn (self $tecnologia) => [
            'value' => $tecnologia->value,
            'label' => $tecnologia->etiqueta(),
        ], self::cases());
    }
}
