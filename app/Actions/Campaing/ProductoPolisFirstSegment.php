<?php
namespace App\Actions\Campaing;

use App\Models\Campaign;
use App\Models\Customer;
use App\Services\CampaignService;
use App\Trait\CampaignFilterTrait;
use App\Enum\FulfillmentStatusEnum;
use App\Repositories\SaleRepository;
use App\Interfaces\CampaingTypeInterface;

class ProductoPolisFirstSegment implements CampaingTypeInterface
{
    use CampaignFilterTrait;
    
    public function __construct(private SaleRepository $saleRepository, private CampaignService $campaignService) {}

    public function firstSegment(Campaign $campaign) : void
    {    
        $query = Customer::withSalesRelations();


        $filters = $this->buildFilters($campaign);
        $lastOrderStart = $campaign->filters['last_order_start'] ?? null;
        $lastOrderEnd = $campaign->filters['last_order_end'] ?? null;
        $limit = $campaign->filters['limit'] ?? null;


        $query = $this->campaignService->applyFilters($query, $filters, $lastOrderStart, $lastOrderEnd);

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