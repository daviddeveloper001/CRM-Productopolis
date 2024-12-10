<?php

namespace App\Services;

use App\Repositories\CityRepository;

class CityServices
{
    public function __construct(private CityRepository $cityRepository) {}

    public function createCity(array $data)
    {
        $city = $this->cityRepository->findBy($data);

        return $city ?: $this->cityRepository->create($data);
    }
}
