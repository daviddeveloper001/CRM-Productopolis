<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentServices
{
    public function __construct(private DepartmentRepository $departmentRepository){}

    public function createDepartment(array $data)
    {
        $department = $this->departmentRepository->findBy($data);
        return $department ?: $this->departmentRepository->create($data);
    }
}