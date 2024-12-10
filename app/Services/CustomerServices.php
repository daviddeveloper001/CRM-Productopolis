<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\SaleRepository;

class CustomerServices
{
    public function __construct(private CustomerRepository $customerRepository){}
    public function createCustomer(array $data)
    {
        $customer = $this->customerRepository->findBy($data);

        return $customer ?: $this->customerRepository->create($data);
    }
}