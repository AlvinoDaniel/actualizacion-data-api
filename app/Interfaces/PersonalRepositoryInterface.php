<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;

interface PersonalRepositoryInterface extends BaseRepositoryInterface
{
    public function searchPersonal($cedula);
}
