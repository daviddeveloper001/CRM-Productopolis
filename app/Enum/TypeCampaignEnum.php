<?php

namespace App\Enum;

enum TypeCampaignEnum: string
{
    case ProductoPolis = 'ProductoPolis';
    case Medical = 'Medical';

    static public function getEvents($value): ?string
    {
        return match ($value) {
            self::Medical->value => EventEnum::class,
            self::ProductoPolis->value => EventProductoPolisEnum::class
        };
    }

    static public function getEventWildcards($value, $event): ?array
    {
        return match ($value) {
            self::Medical->value => EventEnum::getWildcards($event),
            self::ProductoPolis->value => EventProductoPolisEnum::getWildcards($event)
        };
    }
}
