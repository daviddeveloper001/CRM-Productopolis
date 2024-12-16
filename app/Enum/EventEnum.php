<?php

namespace App\Enum;

enum EventEnum: string
{
    case Agendamiento = 'Agendamiento';
    case Demostracion = 'Demostracion';
    case Presupuesto = 'Presupuesto';
    case Compra = 'Compra';

    public function getLabel(): string
    {
        return match ($this) {
            self::Agendamiento => 'Agendamiento',
            self::Demostracion => 'Demostracion',
            self::Presupuesto => 'Presupuesto',
            self::Compra => 'Compra',
        };
    }

}