<?php
namespace App\Repositories;

use App\Models\SegmentType;

class SegmentTypeRepository extends BaseRepository
{
    const RELATIONS = ['sales'];
    public function __construct(SegmentType $segmentType)
    {
        parent::__construct($segmentType, self::RELATIONS);
    }
}