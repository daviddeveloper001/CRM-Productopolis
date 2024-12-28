<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Block;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Support\Facades\DB;
use App\Enum\FulfillmentStatusEnum;
use App\Repositories\SaleRepository;
use App\Interfaces\CampaignActionInterface;
use Illuminate\Support\Facades\Log;

class ProductoPolisAction implements CampaignActionInterface
{
    public function __construct(private SaleRepository $saleRepository) {}
    public function executeCampaign(Block $block): void
    {
        Segment::create([
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



        $campaign->customers()->syncWithoutDetaching($newCustomers->pluck('id')->toArray());


        $this->notifyCustomersWithoutRecentPurchase($campaign, $block);
    }


    public function findCustomersWithoutRecentPurchase(Campaign $campaign, Block $currentBlock)
    {
        // Obtener solo los clientes que no han cumplido el criterio (fulfillment_status != 'fulfilled')
        $customers = DB::table('campaign_customers')
            ->where('campaign_id', $campaign->id)
            ->where('fulfillment_status', '!=', 'fulfilled') // Filtrar clientes que ya cumplieron
            ->get(['customer_id', 'last_purchase_at', 'fulfilled_via_block_id', 'fulfillment_status']);
    
        // Obtener el bloque anterior a la fecha del bloque actual
        $previousBlock = $campaign->blocks()
            ->where('created_at', '<', $currentBlock->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
    
        $customersWithoutRecentPurchase = $customers->map(function ($customer) use ($previousBlock, $currentBlock) {
            // Buscar la última orden del cliente
            $latestOrder = $this->saleRepository->findLastSaleByCustomer($customer->customer_id);
    
            // Si hay una nueva compra más reciente, actualizamos el fulfillment_status
            if ($latestOrder && $latestOrder->date_last_order > $customer->last_purchase_at) {
                DB::table('campaign_customers')
                    ->where('customer_id', $customer->customer_id)
                    ->update([
                        'last_purchase_at' => $latestOrder->date_last_order,
                        'fulfillment_status' => 'fulfilled',
                        'fulfilled_via_block_id' => $previousBlock ? $previousBlock->id : null,
                    ]);
    
                return null; // Cliente ya cumplió con el criterio, se excluye de los pendientes de notificación
            }
    
            // Solo retornamos los clientes que aún no han realizado una compra
            if (!$latestOrder || $latestOrder->date_last_order <= $customer->last_purchase_at) {
                return [
                    'customer_id' => $customer->customer_id,
                    'last_purchase_at' => $customer->last_purchase_at,
                    'fulfillment_status' => $customer->fulfillment_status,
                    'fulfilled_via_block_id' => $customer->fulfilled_via_block_id,
                ];
            }
    
            return null;
        })->filter();
    
        // Crear registros en CustomerSegment solo para clientes que aún no han cumplido
        foreach ($customersWithoutRecentPurchase as $customer) {
            CustomerSegment::create([
                'customer_id' => $customer['customer_id'],
                'segment_id' => $currentBlock->segment->id,
                'last_purchase_at' => $customer['last_purchase_at'],
            ]);
        }
    
        return $customersWithoutRecentPurchase;
    }
    

    public function notifyCustomersWithoutRecentPurchase(Campaign $campaign, Block $currentBlock)
    {

        $customersWithoutRecentPurchase = $this->findCustomersWithoutRecentPurchase($campaign, $currentBlock);

        foreach ($customersWithoutRecentPurchase as $customer) {

            $this->sendNotification($customer);
        }
    }

    protected function sendNotification($customer)
    {


        dd('enviado mensaje ');
    }
}
