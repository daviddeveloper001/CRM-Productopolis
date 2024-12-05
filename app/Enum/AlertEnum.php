<?php

namespace App\Enum;

enum AlertEnum: string
{
    case Top = 'top';
    case Medium = 'medium';
    case Low = 'low';
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Top => 'Top',
            self::Medium => 'Medium bancaria',
            self::Low => 'Low',
        };
    }
}
