<?php

namespace App\Traits;

use App\Models\Departamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

trait DepartamentoTrait {

     /**
   * Validar que existen los departamentos
   * @param Array $dptos
   */
   public function validarDepartamentos($dptos){
    if(in_array('all', $dptos)) return true;

    foreach ($dptos as $departamento_id) {
       $dpto = Departamento::find($departamento_id);
       if(!$dpto){
          throw new Exception('El departamento con id '.$departamento_id.' no existe.');
       }
   }
 }
}
