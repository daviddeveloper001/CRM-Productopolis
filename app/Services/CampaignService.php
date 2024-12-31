<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Customer;
use App\Repositories\CampaignRepository;
use Illuminate\Database\Eloquent\Builder;

class CampaignService
{
    public function __construct(private CampaignRepository $campaignRepository){}

    public function applyFilters($query, array $filters, ?string $lastOrderStart, ?string $lastOrderEnd): Builder
    {
        $query->whereHas('sales', function ($salesQuery) use ($filters, $lastOrderStart, $lastOrderEnd) {
            foreach ($filters as $column => $value) {
                if (!is_null($value)) {
                    $salesQuery->where($column, $value);
                }
            }

            if (!is_null($lastOrderStart) && !is_null($lastOrderEnd)) {
                $salesQuery->whereBetween('date_last_order', [$lastOrderStart, $lastOrderEnd]);
            }
        });

        return $query;
    }
}