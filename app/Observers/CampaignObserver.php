<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Models\Customer;
use App\Enum\TypeCampaignEnum;
use App\Enum\FulfillmentStatusEnum;
use App\Repositories\SaleRepository;
use App\Factory\CampaingFirstSegmentFactory;

class CampaignObserver
{

    public function created(Campaign $campaign): void
    {

        $factoryCampaign = CampaingFirstSegmentFactory::getAction($campaign->type_campaign);

        if ($factoryCampaign) {
            try {
                $factoryCampaign->firstSegment($campaign);
            } catch (\Throwable $th) {
                //throw $th;
            }
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
