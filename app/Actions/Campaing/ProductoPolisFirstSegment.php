<?php
namespace App\Actions\Campaing;

use App\Models\Campaign;
use App\Models\Customer;
use App\Enum\FulfillmentStatusEnum;
use App\Repositories\SaleRepository;
use App\Interfaces\CampaingTypeInterface;

class ProductoPolisFirstSegment implements CampaingTypeInterface
{
    public function __construct(private SaleRepository $saleRepository) {}

    public function firstSegment(Campaign $campaign) : void
    {    
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
}