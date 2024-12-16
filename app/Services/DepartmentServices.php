<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentServices
{
    public function __construct(private DepartmentRepository $departmentRepository){}

    public function createDepartment(string $name)
    {

        $searchCriteria = ['name' => $name];

        $departmentData = ['name' => $name];

        $department = $this->departmentRepository->findBy($searchCriteria);
        return $department ?: $this->departmentRepository->create($departmentData);
    }   
}