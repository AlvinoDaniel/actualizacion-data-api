<?php

namespace App\Interfaces;

use App\Interfaces\BaseRepositoryInterface;

interface GrupoRepositoryInterface extends BaseRepositoryInterface 
{
   public function allGrupos();
   public function registrarGrupo(array $data, array $departamentos);
   public function actualizarGrupo(array $data, array $departamentos, $id);
   public function obtenerGrupo($id);
   public function eliminarGrupo($id);
   public function eliminarDepartamentoGrupo($id_grupo,$id_dpto);
   public function agregarDepartamentoGrupo($id_grupo,$id_dpto);
   public function validarDepartamentos(Array $dptos);
}