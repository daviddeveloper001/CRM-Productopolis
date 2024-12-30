<?php

namespace App\Factory;


use App\Enum\TypeCampaignEnum;
use App\Interfaces\CampaingTypeInterface;
use App\Actions\Campaing\MedicalFirstSegment;
use App\Actions\Campaing\ProductoPolisFirstSegment;

class CampaingFirstSegmentFactory
{
    public static function getAction(string $typeCampaign): ?CampaingTypeInterface
    {
        return match ($typeCampaign) {
            TypeCampaignEnum::Medical->value => app()->make(MedicalFirstSegment::class),
            TypeCampaignEnum::ProductoPolis->value => app()->make(ProductoPolisFirstSegment::class),
            default => null,
        };
    }
}