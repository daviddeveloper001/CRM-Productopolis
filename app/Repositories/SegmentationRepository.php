<?php

namespace App\Repositories;

use App\Models\Segmentation;

class SegmentationRepository extends BaseRepository 
{
    const RELATIONS = ['sales', 'registers'];

    public function __construct(Segmentation $segmentation)
    {
        parent::__construct($segmentation, self::RELATIONS);
    }
}