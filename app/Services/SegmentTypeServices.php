<?php

namespace App\Services;

use App\Repositories\SegmentTypeRepository;

class SegmentTypeServices
{
    public function __construct(private SegmentTypeRepository $segmentTypeRepository) {}

    public function createSegmentType(array $data) {
        $segmentType = $this->segmentTypeRepository->findBy($data);
        return $segmentType ?: $this->segmentTypeRepository->create($data);
    }
}