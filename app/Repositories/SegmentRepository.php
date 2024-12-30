<?php

namespace App\Repositories;

use App\Models\Segment;

class SegmentRepository extends BaseRepository 
{
    const RELATIONS = ['sales', 'registers', 'customers', 'block'];

    public function __construct(Segment $segment)
    {
        parent::__construct($segment, self::RELATIONS);
    }
}