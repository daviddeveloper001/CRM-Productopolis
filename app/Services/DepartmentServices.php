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

        if ($departmentData == null) {
            return $departmentData = 'Not found';
        }

        $department = $this->departmentRepository->findBy($searchCriteria);
        return $department ?: $this->departmentRepository->create($departmentData);
    }   
}