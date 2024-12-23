<?php

namespace App\Enum;

enum EventProductoPolisEnum: string
{
    case Venta = 'Venta';

    static public function getWildcards($value): array
    {
        return match ($value) {
            self::Venta->value => [
                '[NOMBRE-CLIENTE]',
                '[TELEFONO-CLIENTE]',
                '[EMAIL-CLIENTE]',
                '[CIUDAD-CLIENTE]'
            ]
        };
    }
}
