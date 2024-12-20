<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Block;
use App\Models\Customer;
use App\Models\Segmentation;
use App\Enum\TypeCampaignEnum;
use App\Models\SegmentRegister;
use Illuminate\Console\Command;
use App\Factory\BlockActionFactory;
use App\Factory\CampaignActionFactory;
use Illuminate\Support\Facades\Log;

class ProcessBlocksCommand extends Command
{
    protected $signature = 'blocks:process';
    protected $description = 'Procesa los bloques cuya fecha start_date coincide con el tiempo actual';

    public function handle()
    {
        $now = Carbon::now()->floorMinute();

        $upperLimit = $now->copy()->addMinutes(2);

        $blocks = Block::whereBetween('start_date', [$now, $upperLimit])->get();


        if ($blocks->isEmpty()) {
            log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }

        foreach ($blocks as $block) {


            Segmentation::create([
                'block_id' => $block->id
            ]);
            

            $campaign = $block->campaign;

            $typeCampaign = CampaignActionFactory::getAction($campaign->type_campaign);


            if ($typeCampaign) {
                try {
                    $typeCampaign->executeCampaign($block);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }

            /* if ($campaign->type_campaign == TypeCampaignEnum::Medical->value) {
                $action = BlockActionFactory::getAction($block->exit_criterion);

                $country = $campaign->filters['country'];
                $isLead = $campaign->filters['is_lead'];
                $exists = $campaign->filters['exists'] ? '1' : '0';
                $createdSince = $campaign->filters['created_since'];
                $startDate = $campaign->filters['start_date'];
                $endDate = $campaign->filters['end_date'];
                $nextStepExecuted = $campaign->filters['next_step_executed'];

                if ($action) {
                    try {
                        $action->execute($block, [
                            'country' => $country,
                            'is_lead' => $isLead,
                            'exists' => $exists,
                            'created_since' => $createdSince,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'next_step_executed' => $nextStepExecuted
                        ]);
                        Log::info("Acción ejecutada para el bloque: {$block->id}");
                    } catch (\Exception $e) {
                        Log::error("Error al ejecutar la acción para el bloque {$block->id}: {$e->getMessage()}");
                    }
                } else {
                    Log::warning("No se encontró acción para el criterio: {$block->exit_criterion}");
                }
            }


            if ($campaign->type_campaign == TypeCampaignEnum::ProductoPolis->value) {

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


                foreach ($data as $customer) {
                    SegmentRegister::create([
                        'segment_id' => $segment->id,
                        'customer_id' => $customer->id,
                    ]);
                }
            } */
        }
    }
}
