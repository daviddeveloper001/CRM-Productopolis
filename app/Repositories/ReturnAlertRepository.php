<?php

namespace App\Repositories;

use App\Models\ReturnAlert;

class ReturnAlertRepository extends BaseRepository 
{
    const RELATIONS = ['sales'];

    public function __construct(ReturnAlert $returnAlert)
    {
        parent::__construct($returnAlert, self::RELATIONS);
    }
}