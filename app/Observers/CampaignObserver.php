<?php

namespace App\Observers;

use App\Enum\FulfillmentStatusEnum;
use App\Models\Campaign;
use App\Models\Customer;
use App\Enum\TypeCampaignEnum;
use App\Repositories\SaleRepository;

class CampaignObserver
{
    public function __construct(private SaleRepository $saleRepository) {}
    public function created(Campaign $campaign): void
    {


        if ($campaign->type_campaign == TypeCampaignEnum::ProductoPolis->value) {

            $query = Customer::with([
                'sales',
                'sales.paymentMethod',
                'sales.shop',
                'sales.seller',
                'sales.returnAlert',
                'sales.segmentType',
            ]);


            $campaignFilters = $campaign->filters ?? [];
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

            $campaignCustomerData = $customers->mapWithKeys(function ($customer) {
                $lastOrder = $this->saleRepository->findLastSaleByCustomer($customer->id);
                return [
                    $customer->id => [
                        'last_purchase_at' => $lastOrder ? $lastOrder->date_last_order : null,
                        'fulfillment_status' => FulfillmentStatusEnum::Pending->value, // Marcamos como pendiente por defecto
                        'fulfilled_via_block_id' => null,
                    ],
                ];
            });

            // Asociar los clientes con la campaña y guardar la fecha de la última compra
            $campaign->customers()->sync($campaignCustomerData->toArray());
        }


        if ($campaign->type_campaign == TypeCampaignEnum::Medical->value) {
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
