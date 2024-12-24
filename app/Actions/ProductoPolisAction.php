<?php

namespace App\Actions;

use App\Models\Block;
use App\Models\Customer;
use App\Models\CustomerSegment;
use App\Interfaces\CampaignActionInterface;



class ProductoPolisAction implements CampaignActionInterface
{
    public function executeCampaign(Block $block): void
    {
        $query = Customer::with([
            'sales',
            'sales.paymentMethod',
            'sales.shop',
            'sales.seller',
            'sales.returnAlert',
            'sales.segmentType',
        ]);


        $campaignFilters = $block->campaign->filters ?? [];
        $filters = [
            'payment_method_id' => $campaignFilters['payment_method_id'] ?? null,
            'return_alert_id'   => $campaignFilters['alert'] ?? null,
            'department_id'     => $campaignFilters['department_id'] ?? null,
            'city_id'           => $campaignFilters['city_id'] ?? null,
            'seller_id'         => $campaignFilters['seller_id'] ?? null,
            'shop_id'           => $campaignFilters['shop_id'] ?? null,
            'segment_type_id'   => $campaignFilters['segment_type_id'] ?? null,
        ];

        $last_order_start = $campaignFilters['last_order_start'] ?? null;
        $last_order_end = $campaignFilters['last_order_end'] ?? null;
        $limit = $campaignFilters['limit'] ?? null;


        $query->whereHas('sales', function ($salesQuery) use ($filters, $last_order_start, $last_order_end) {
            foreach ($filters as $column => $value) {
                if (!is_null($value)) {
                    $salesQuery->where($column, $value);
                }
            }


            if (!is_null($last_order_start) && !is_null($last_order_end)) {

                $salesQuery->whereBetween('date_last_order', [$last_order_start, $last_order_end]);
            }
        });

        if (!is_null($limit)) {
            $query->limit($limit);
        }



        $customers = $query->get();

        foreach ($customers as $customer) {
            CustomerSegment::create([
                'customer_id' => $customer->id,
                'segment_id' => $block->segment->id,
            ]);
        }
    }
}
