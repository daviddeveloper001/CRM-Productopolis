<?php
namespace App\Trait;

use App\Models\Campaign;

trait CampaignFilterTrait
{
    public function buildFilters(Campaign $campaign): array
    {
        $campaignFilters = $campaign->filters ?? [];
        return [
            'payment_method_id' => $campaignFilters['payment_method_id'] ?? null,
            'return_alert_id'   => $campaignFilters['alert'] ?? null,
            'department_id'     => $campaignFilters['department_id'] ?? null,
            'city_id'           => $campaignFilters['city_id'] ?? null,
            'seller_id'         => $campaignFilters['seller_id'] ?? null,
            'shop_id'           => $campaignFilters['shop_id'] ?? null,
            'segment_type_id'   => $campaignFilters['segment_type_id'] ?? null,
        ];
    }
}
