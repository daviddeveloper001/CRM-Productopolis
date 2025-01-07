<?php
namespace App\Factory;


use App\Enum\EventEnum;
use App\Actions\FirstSegmentMedical\BuyAction;
use App\Actions\FirstSegmentMedical\BudgetAction;
use App\Actions\FirstSegmentMedical\SchedulingAction;

use Illuminate\Support\Facades\Log;
use App\Actions\FirstSegmentMedical\DemonstrationAction;
use App\Interfaces\CampaignFirstSegmentMedicalInterface;


class CampaingFirstSegementMedicalActionFactory
{
    public static function getAction(string $exitCriterion): ?CampaignFirstSegmentMedicalInterface
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
