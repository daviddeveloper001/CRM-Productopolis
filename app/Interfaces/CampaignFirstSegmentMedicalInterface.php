<?php
namespace App\Interfaces;

use App\Models\Campaign;

interface CampaignFirstSegmentMedicalInterface
{
    public function executeFirtstSegmentMedical(Campaign $campaign, array $filters): void;
}

