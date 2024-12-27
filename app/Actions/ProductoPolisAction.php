<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Block;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Support\Facades\DB;
use App\Repositories\SaleRepository;
use App\Interfaces\CampaignActionInterface;


class ProductoPolisAction implements CampaignActionInterface
{
    public function __construct(private SaleRepository $saleRepository) {}
    public function executeCampaign(Block $block): void
    {
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

        $campaign = $block->campaign;
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

        $query->get();


        $campaign = $block->campaign;

        // Excluir clientes ya asociados a la campaña
        $existingCustomerIds = DB::table('campaign_customers')
            ->where('campaign_id', $campaign->id)
            ->pluck('customer_id');
        $newCustomers = $query->whereNotIn('id', $existingCustomerIds)->get();


        // Asociar nuevos clientes al segmento del bloque

        //$segment->customers()->syncWithoutDetaching($newCustomers->pluck('id')->toArray());

        $campaign->customers()->syncWithoutDetaching($newCustomers->pluck('id')->toArray());

        $this->notifyCustomersWithoutRecentPurchase($campaign);
    }


    public function findCustomersWithoutRecentPurchase(Campaign $campaign)
    {
        $customers = DB::table('campaign_customers')
            ->where('campaign_id', $campaign->id)
            ->get(['customer_id', 'last_purchase_at']);

        $customersWithoutRecentPurchase = $customers->map(function ($customer) {
            $latestOrder = $this->saleRepository->findLastSaleByCustomer($customer->customer_id);

            if (!$latestOrder || $latestOrder->date_last_order <= $customer->last_purchase_at) {
                return [
                    'customer_id' => $customer->customer_id,
                    'last_purchase_at' => $customer->last_purchase_at,
                ];
            }

            return null;
        })->filter();


        return $customersWithoutRecentPurchase;
    }



    public function notifyCustomersWithoutRecentPurchase(Campaign $campaign)
    {

        $customersWithoutRecentPurchase = $this->findCustomersWithoutRecentPurchase($campaign);

        foreach ($customersWithoutRecentPurchase as $customer) {
            $this->sendNotification($customer);
        }
    }

    protected function sendNotification($customer)
    {
        dd('enviado mensaje ');
    }
}
