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

    static public function getWildcards($value): array
    {
        return match ($value) {
            self::Agendamiento->value => [
                '[NOMBRE-CLIENTE]',
                '[TELEFONO-CLIENTE]',
                '[EMAIL-CLIENTE]',
                '[CIUDAD-CLIENTE]',
                '[FECHA-INICIO-AGENDA]',
                '[HORA-INICIO-AGENDA]',
                '[FECHA-FIN-AGENDA]',
                '[HORA-FIN-AGENDA]',
                '[TITULO-AGENDA]',
                '[DESCRIPCION-AGENDA]'
            ],
            self::Demostracion->value => [
                '[NOMBRE-CLIENTE]',
                '[TELEFONO-CLIENTE]',
                '[EMAIL-CLIENTE]',
                '[CIUDAD-CLIENTE]',
                '[FECHA-INICIO-AGENDA]',
                '[HORA-INICIO-AGENDA]',
                '[FECHA-FIN-AGENDA]',
                '[HORA-FIN-AGENDA]',
                '[TITULO-AGENDA]',
                '[DESCRIPCION-AGENDA]'
            ]
        };
    }
}
