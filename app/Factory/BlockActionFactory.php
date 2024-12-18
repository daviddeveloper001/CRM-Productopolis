<?php
namespace App\Factory;


use App\Enum\EventEnum;
use App\Actions\BuyAction;
use App\Actions\BudgetAction;
use App\Actions\SchedulingAction;

use Illuminate\Support\Facades\Log;
use App\Actions\DemonstrationAction;
use App\Interfaces\BlockActionInterface;


class BlockActionFactory
{
    public static function getAction(string $exitCriterion): ?BlockActionInterface
    {
        return match ($exitCriterion) {
            EventEnum::Agendamiento->value => app()->make(SchedulingAction::class),
            EventEnum::Demostracion->value => app()->make(DemonstrationAction::class),
            EventEnum::Presupuesto->value => app()->make(BudgetAction::class),
            EventEnum::Compra->value => app()->make(BuyAction::class),
            default => null,
        };
    }
}
