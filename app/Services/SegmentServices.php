<?php

namespace App\Services;

use App\Repositories\SegmentRepository;

class SegmentServices
{
    public function __construct(private SegmentRepository $segmentRepository){}
    public function createSegment(int $blockId)
    {
        $segmentData = [
            'block_id' => $blockId,
        ];
        return $this->segmentRepository->create($segmentData);
    }
}