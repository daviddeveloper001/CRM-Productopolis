<?php

namespace App\Repositories;

use App\Models\Campaign;

class CampaignRepository extends BaseRepository
{

    const RELATIONS = [
        'blocks',
        'customers',
    ];

    public function __construct(Campaign $campaign)
    {
        parent::__construct($campaign, self::RELATIONS);
    }
}