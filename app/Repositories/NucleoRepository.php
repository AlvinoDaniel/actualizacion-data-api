<?php

namespace App\Repositories;

use App\Interfaces\NucleoRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Nucleo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class NucleoRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Nucleo $nucleo)
  {
      $this->model = $nucleo;
  }

}
