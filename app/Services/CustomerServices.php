<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Customer;
use App\Repositories\SaleRepository;
use App\Repositories\CustomerRepository;

class CustomerServices
{
    public function __construct(private CustomerRepository $customerRepository) {}
    public function createCustomer(array $data, int $cityId, int $countryId): Customer
    {
        $searchCriteria = [
            'phone' => $data['telefono']
        ];
        $customer = $this->customerRepository->findBy($searchCriteria);

        if (!$customer) {

            $fullName = explode(' ', trim($data['nombre_cliente']));
            $firstName = $fullName[0] ?? null;
            $lastName = implode(' ', array_slice($fullName, 1));
            
            $baseEmail = strtolower($firstName . '.' . $lastName) . '@example.com';

            $customerData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $data['telefono'],
                'email' => $baseEmail,
                'is_frequent_customer' => $data['es_comun'] ?? false,
                'city_id' => $cityId,
                'country_id' => $countryId,
            ];

            $customer = $this->customerRepository->create($customerData);
        }


        return $customer;
    }


}
