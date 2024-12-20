<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository extends BaseRepository 
{
    const RELATIONS = ['customers'];

    public function __construct(Country $country)
    {
        parent::__construct($country, self::RELATIONS);
    }
}