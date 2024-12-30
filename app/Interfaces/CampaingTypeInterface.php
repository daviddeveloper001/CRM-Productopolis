<?php

namespace App\Interfaces;

use App\Models\Campaign;

interface CampaingTypeInterface
{
    public function firstSegment(Campaign $campaign) : void;
}