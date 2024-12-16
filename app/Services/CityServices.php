<?php

namespace App\Services;

use App\Models\City;
use App\Repositories\CityRepository;

class CityServices
{
    public function __construct(private CityRepository $cityRepository) {}

    public function createCity(string $cityName, int $departmentId) : City
    {

        
        $searchCriteria = [
            'name' => $cityName
        ];

        $city = $this->cityRepository->findBy($searchCriteria);

        if (!$city) {
            $cityData = [
                'name' => $cityName,
                'department_id' => $departmentId,
            ];

            $city = $this->cityRepository->create($cityData);
        }

        return $city;
    }
}
