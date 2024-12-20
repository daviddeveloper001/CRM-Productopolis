<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Segmentation;
use App\Enum\TypeCampaignEnum;
use App\Services\SegmentationServices;
use App\Jobs\ProcessConsultationMedical;

class CampaigngObserver
{
    public function __construct(private SegmentationServices $segmentationServices){}
    public function created(Campaign $campaign): void
    {

        $segment = Segmentation::create([
            'campaign_id' => $campaign->id
        ]);

        //$this->segmentationServices->createSegmentation([$campaign->id]);

        if ($campaign->type_campaign == TypeCampaignEnum::Medical->value) {
            ProcessConsultationMedical::dispatch($campaign);            
        }



        if ($campaign->type_campaign == TypeCampaignEnum::ProductoPolis->value) {
            $query = Customer::with(['sales', 'sales.paymentMethod', 'sales.shop', 'sales.seller', 'sales.returnAlert', 'sales.segmentType']);

            dd($query);

            $filters = [
                'payment_method_id' => $campaign->filters['payment_method_id'] ?? null,
                'return_alert_id'   => $campaign->filters['return_alert_id'] ?? null,
                'department_id'     => $campaign->filters['department_id'] ?? null,
                'city_id'           => $campaign->filters['city_id'] ?? null,
                'seller_id'         => $campaign->filters['seller_id'] ?? null,
                'shop_id'           => $campaign->filters['shop_id'] ?? null,
                'segment_type_id'   => $campaign->filters['segment_type_id'] ?? null,
            ];


            $query->whereHas('sales', function ($salesQuery) use ($filters) {
                foreach ($filters as $column => $value) {
                    if (!is_null($value)) {
                        // Dependiendo del filtro, aplicar las condiciones correctas de forma específica
                        if (in_array($column, ['payment_method_id', 'return_alert_id', 'shop_id', 'seller_id'])) {
                            $salesQuery->where($column, $value); // Campos que pertenecen al modelo Sale
                        }
                    }
                }
            });


            dd($query->get());
        }
    }

    /**
     * Handle the Campaign "updated" event.
     */
    public function updated(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "deleted" event.
     */
    public function deleted(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "restored" event.
     */
    public function restored(Campaign $campaign): void
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     */
    public function forceDeleted(Campaign $campaign): void
    {
        //
    }
}
