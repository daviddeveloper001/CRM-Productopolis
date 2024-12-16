<?php

namespace App\Enum;

enum EventEnum: string
{
    case Agendamiento = 'Agendamiento';
    case Demostracion = 'Demostracion';
    case Presupuesto = 'Presupuesto';
    case Compra = 'Compra';

    public static function fromName(string $name)
    {
        return constant("self::$name");
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Agendamiento => 'Agendamiento',
            self::Demostracion => 'Demostracion',
            self::Presupuesto => 'Presupuesto',
            self::Compra => 'Compra',
        };
    }

    public function getWildcards(): array
    {
        return match ($this) {
            self::Agendamiento => [
                '[NOMBRE-CLIENTE]',
                '[TELEFONO-CLIENTE]',
                '[EMAIL-CLIENTE]',
                '[CIUDAD-CLIENTE]',
                '[EVENT-START-DATE]',
                '[EVENT-START-TIME]',
                '[EVENT-END-DATE]',
                '[EVENT-END-TIME]',
                '[EVENT-TITLE]',
                '[EVENT-DESCRIPTION]'
            ],
            self::Demostracion => [
                '[NOMBRE-CLIENTE]',
                '[TELEFONO-CLIENTE]',
                '[EMAIL-CLIENTE]',
                '[CIUDAD-CLIENTE]',
                '[EVENT-START-DATE]',
                '[EVENT-START-TIME]',
                '[EVENT-END-DATE]',
                '[EVENT-END-TIME]',
                '[EVENT-TITLE]',
                '[EVENT-DESCRIPTION]'
            ]
        };
    }
}
