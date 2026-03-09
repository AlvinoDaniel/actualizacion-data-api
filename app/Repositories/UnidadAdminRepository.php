<?php

namespace App\Repositories;

use App\Models\UnidadAdministrativa;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class UnidadAdminRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(UnidadAdministrativa $nucleo)
  {
      $this->model = $nucleo;
  }

}
