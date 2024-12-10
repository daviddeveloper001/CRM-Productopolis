<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository extends BaseRepository 
{
    const RELATIONS = ['cities'];

    public function __construct(Department $department)
    {
        parent::__construct($department, self::RELATIONS);
    }
}