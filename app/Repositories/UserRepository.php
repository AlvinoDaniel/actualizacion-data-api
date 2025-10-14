<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use App\Models\Personal;
use App\Models\UnidadAdministrativa;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(User $user)
  {
      $this->model = $user;
  }

  /**
   * @param Array $cedula
   */
  public function search($cedula){

    try {
      $personal = Personal::where('cedula_identidad', $cedula)->where('jefe', true)->first();

      if(!$personal){
        throw new Exception('El funcionario que desea registrar no tiene acceso a este Módulo.', 422);
      }

      $usuario=  User::where('cedula', $cedula)->first();

      if($usuario){
        throw new Exception('El funcionario ya tiene un usuario registrado.', 422);
      }


      return [
        "id"        => $personal->id,
        "email"     => $personal->correo,
      ];
    } catch (\Throwable $th) {
       throw new Exception($th->getMessage(), $th->getCode());
    }
 }

  /**
   * @param Array $cedula
   */
  public function search_user($cedula){

    try {
      $personal = Personal::where('cedula_identidad', $cedula)->where('jefe', true)->first();

      if(!$personal){
        throw new Exception('El funcionario no esta registrado en nuestro sistema.', 422);
      }
      return [
        "id"        => $personal->id,
        "email"     => $personal->correo,
      ];
    } catch (\Throwable $th) {
       throw new Exception($th->getMessage(), $th->getCode());
    }
 }

 public function registrarUsuario($data){

    $userData = [
        'cedula'                => $data['cedula'],
        // 'email'                 => $data['correo'],
        'password'              => $data['password'],
        'personal_id'           => $data['personal_id'],
        'status'                => 1,
    ];
    try {
        $usuario=  User::where('cedula', $data['cedula'])->first();

        if($usuario){
          throw new Exception('El funcionario ya tiene un usuario registrado.', 422);
        }

        $user = User::create($userData);
        return $user;
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
    }

 }

 public function actualizarUsuario($request){

    $data_personal = Auth::user()->personal;
    try {
        $personal =  Personal::find($data_personal->id);

        $personal->update($request->except('correos_dependencia'));
        $personal->refresh();

        if($request->correos_dependencia && count($request->correos_dependencia) > 0){
          foreach ($request->correos_dependencia as $item) {
            $dependencia = UnidadAdministrativa::find($item["id"]);
            if($dependencia){
              $dependencia->update([
                "correo_dependencia"  => $item["value"]
              ]);
            }
          }
        }
        return $personal;
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
    }

 }



}
