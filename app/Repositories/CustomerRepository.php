<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository 
{
    const RELATIONS = ['cities', 'sales'];

    public function __construct(Customer $customer)
    {
        parent::__construct($customer, self::RELATIONS);
    }
}