<?php
namespace App\Interfaces;

use App\Models\Block;
use App\Models\Campaign;

interface CampaignActionInterface
{
    public function executeCampaign(Block $block): void;
}

