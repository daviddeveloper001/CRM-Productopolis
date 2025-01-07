<?php
namespace App\Actions\Campaing;

use App\Models\Campaign;
use App\Enum\UserTypeEnum;
use App\Interfaces\CampaingTypeInterface;
use App\Factory\CampaingFirstSegementMedicalActionFactory;

class MedicalFirstSegment implements CampaingTypeInterface
{
    public function firstSegment(Campaign $campaign) : void
    {
        $exitCriterion = $campaign->filters['exit_criterion'];
        $action = CampaingFirstSegementMedicalActionFactory::getAction($exitCriterion);

        
        $country = $campaign->filters['country'];
        $isLead = $campaign->filters['is_lead'] == UserTypeEnum::LEAD->value ? '1' : '0';
        $exists = $campaign->filters['exists'] ? '1' : '0';
        $createdSince = $campaign->filters['created_since'];
        $startDate = $campaign->filters['start_date'];
        $endDate = $campaign->filters['end_date'];
        $nextStepExecuted = $campaign->filters['next_step_executed'] ? '1' : '0';

        if ($action) {
            try {
                $action->executeFirtstSegmentMedical($campaign,[
                    'country' => $country,
                    'is_lead' => $isLead,
                    'exists' => $exists,
                    'created_since' => $createdSince,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'next_step_executed' => $nextStepExecuted
                ]);
            } catch (\Exception $e) {
            }
        } else {
        }
    }
}