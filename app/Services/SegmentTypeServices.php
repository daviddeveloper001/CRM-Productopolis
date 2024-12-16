<?php

namespace App\Services;

use App\Repositories\SegmentTypeRepository;

class SegmentTypeServices
{
    public function __construct(private SegmentTypeRepository $segmentTypeRepository) {}

    public function createSegmentType(string $name) {

        $searchCriteria = ['name' => $name];

        // Preparamos el array de datos para crear
        $segmentData = ['name' => $name];

        $segmentType = $this->segmentTypeRepository->findBy($searchCriteria);
        return $segmentType ?: $this->segmentTypeRepository->create($segmentData);
    }
}