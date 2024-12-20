<?php
namespace App\Factory;



use App\Actions\MedicalAction;

use App\Enum\TypeCampaignEnum;
use App\Actions\SchedulingAction;
use App\Actions\DemonstrationAction;
use App\Actions\ProductoPolisAction;
use App\Interfaces\CampaignActionInterface;


class CampaignActionFactory
{
    public static function getAction(string $type_campaign): ?CampaignActionInterface
    {
        return match ($type_campaign) {
            TypeCampaignEnum::Medical->value => app()->make(MedicalAction::class),
            TypeCampaignEnum::ProductoPolis->value => app()->make(ProductoPolisAction::class),
            default => null,
        };
    }
}
