<?php

namespace App\Services;

use App\Repositories\SegmentationRepository;

class SegmentationServices
{
    public function __construct(private SegmentationRepository $segmentationRepository){}
    public function createSegmentation(array $data)
    {
        $segmentation = $this->segmentationRepository->findBy($data);
        return $segmentation ?: $this->segmentationRepository->create($data);
    }
}