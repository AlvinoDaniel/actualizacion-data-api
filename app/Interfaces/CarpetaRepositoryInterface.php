<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;

interface CarpetaRepositoryInterface extends BaseRepositoryInterface 
{
   public function allCarpetas();
}