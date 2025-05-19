<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class Baserepository implements BaseRepositoryInterface {

  protected $model; 

  
  public function __construct(Model $model)
  {
      $this->model = $model;
  }

  public function all(array $relations = []){
    return $this->model->with($relations)->get();
  }

  public function findById($id){
    $model = $this->model->find($id);
    if(null === $model){
      throw new ModelNotFoundException($this->model::NAME.' No existe en nuestros registros.',422);
    }

    return $model;

  }

  public function registrar(array $data){
    try {
      $model = $this->model->create($data);
      return $model;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage());
    }
  }

  public function actualizar(array $data, $id){
    try {
      $model = $this->findById($id);
      $model->update($data);
      $model->refresh();
      return $model;
      
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
  }

  public function delete($id){
    try {
      return $this->findById($id)->delete();
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
  }

}