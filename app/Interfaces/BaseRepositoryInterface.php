<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all(array $relations);

    public function registrar(array  $data);

    public function actualizar(array $data, $id);

    public function delete($id);

    public function findById($id);
}