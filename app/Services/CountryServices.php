<?php

namespace App\Services;

use App\Repositories\CountryRepository;

class CountryServices
{
    public function __construct(private CountryRepository $countryRepository){}

    public function createCountry(string $name)
    {
        if (trim($name) === '') {
            $name = 'Colombia';
        }
    
        $searchCriteria = ['name' => $name];
        $countryData = ['name' => $name];
    
        $country = $this->countryRepository->findBy($searchCriteria);
        return $country ?: $this->countryRepository->create($countryData);
    }
      
}