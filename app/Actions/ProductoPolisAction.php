<?php
namespace App\Actions;

use App\Models\Block;
use App\Models\Customer;
use App\Models\SegmentRegister;
use App\Interfaces\CampaignActionInterface;



class ProductoPolisAction implements CampaignActionInterface
{
    public function executeCampaign(Block $block,): void
    {
        $query = Customer::with(['sales', 'sales.paymentMethod', 'sales.shop', 'sales.seller', 'sales.returnAlert', 'sales.segmentType']);

                $filters = [
                    'payment_method_id' => $campaign->filters['payment_method_id'] ?? null,
                    'return_alert_id'   => $campaign->filters['alert'] ?? null,
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

                $data = $query->get();

                dd($data);


                foreach ($data as $customer) {
                    SegmentRegister::create([
                        'segment_id' => $block->segment->id,
                        'customer_id' => $customer->id,
                    ]);
                }
    }
}
