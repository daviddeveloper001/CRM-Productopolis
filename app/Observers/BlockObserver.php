<?php

namespace App\Observers;

use App\Models\Block;
use App\Models\Segment;
use App\Models\Customer;
use App\Enum\TypeCampaignEnum;
use App\Models\CustomerSegment;

class BlockObserver
{
    /**
     * Handle the Block "created" event.
     */
    public function created(Block $block): void
    {

        if($block->campaign->type_campaign == TypeCampaignEnum::ProductoPolis->value) {
            $segment = Segment::create([
                'block_id' => $block->id
            ]);
            
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
                    'segment_id' => $segment->id,
                ]);
            }
        }
        
    }

    /**
     * Handle the Block "updated" event.
     */
    public function updated(Block $block): void
    {
        //
    }

    /**
     * Handle the Block "deleted" event.
     */
    public function deleted(Block $block): void
    {
        //
    }

    /**
     * Handle the Block "restored" event.
     */
    public function restored(Block $block): void
    {
        //
    }

    /**
     * Handle the Block "force deleted" event.
     */
    public function forceDeleted(Block $block): void
    {
        //
    }
}
