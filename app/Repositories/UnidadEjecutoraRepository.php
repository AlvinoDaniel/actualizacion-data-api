<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\UnidadEjecutora;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class UnidadEjecutoraRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(UnidadEjecutora $nucleo)
  {
      $this->model = $nucleo;
  }

}
