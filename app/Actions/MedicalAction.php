<?php

namespace App\Actions;

use App\Enum\UserTypeEnum;
use App\Models\Block;
use App\Models\Campaign;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CampaignActionInterface;

class MedicalAction implements CampaignActionInterface
{
    public function executeCampaign(Block $block,): void
    {
        $action = BlockActionFactory::getAction($block->exit_criterion);

        $country = $block->campaign->filters['country'];
        $isLead = $block->campaign->filters['is_lead'] == UserTypeEnum::LEAD->value ? '1' : '0';
        $exists = $block->campaign->filters['exists'] ? '1' : '0';
        $createdSince = $block->campaign->filters['created_since'];
        $startDate = $block->campaign->filters['start_date'];
        $endDate = $block->campaign->filters['end_date'];
        $nextStepExecuted = $block->campaign->filters['next_step_executed'] ? '1' : '0';


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
}
