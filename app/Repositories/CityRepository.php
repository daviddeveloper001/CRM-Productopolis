<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository 
{
    const RELATIONS = ['department'];

    public function __construct(City $city)
    {
        parent::__construct($city, self::RELATIONS);
    }
}