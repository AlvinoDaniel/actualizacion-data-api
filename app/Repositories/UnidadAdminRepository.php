<?php

namespace App\Repositories;

use App\Models\UnidadAdministrativa;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;


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

  public function allUnidadByNucleo(Request $requets){
    try {
      $unidades = UnidadAdministrativa::with(['nucleo','escuela','unidad_padre']);

      if($requets->has('nucleo')){
        $unidades->where('cod_nucleo', $requets->nucleo);
      }
      $data = $unidades->get();
      return $data;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
  }

}
