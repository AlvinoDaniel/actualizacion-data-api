<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
// use App\Http\Requests\ChangePasswordRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserMeResource;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\UserAuthCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AuthController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * Login de usuario
     *
     * [Se envia email y contraseña para aceeder a la sesión.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  password string required Contraseña de usuario.
     *
     * @responseFile  responses/AuthController/login.post.json
     *
    */
   public function login(LoginRequest $request){
      $tipo_accion =  'Login';

      try {
         $user = User::where('cedula', $request->cedula)
                  ->first();
         if(!$user){
            return $this->sendError('El Funcionario no existe en nuestros registros.');
         }

         if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Las credenciales no concuerdan. Usuario o Contraseña inválida',);
         }

         $token = $user->createToken('TokenCultorApi-'.$user->name)->plainTextToken;
         $message = 'Usuario Autenticado exitosamente.';

         return $this->sendResponse(['token' => $token ], $message);

      } catch (\Throwable $th) {
         return $this->sendError('Ocurrio un error, contacte al administrador '.$th->getMessage());
      }
   }

   /**
     * Obtener información de usuario logeado.
     *
     * [Petición para obtener informacion del usuario logeado..]
     *
     * @responseFile  responses/AuthController/me.get.json
     *
    */
   public function me()
   {
      $user = Auth::user()->load(['personal', 'permissions', 'personal.nucleo', 'personal.tipoPersonal', 'personal.cargoJefe', 'personal.unidades.entidad.escuela', 'personal.unidades.entidad.subunidades']);

      return $this->sendResponse([ 'user' => new UserMeResource($user) ], 'Datos de Usuario Logeado');
   }

     /**
     * Cerrar sesión.
     *
     * [Petición para cerrar sesión.]
     *
     * @response
        {
            "message": "Sesión cerrada con exito."
        }
     *
    */

    public function logout(){
      $user = Auth::user();
      try {
          //Auth::user()->currentAccessToken()->delete();
         $user->tokens()->delete();
         return $this->sendSuccess('Sesión cerrada con exito.');
      } catch (\Throwable $th) {
         return $this->sendError('Ocurrio un error al intentar cerrar la sesion.', 422);
      }
  }

   /**
     * Cambiar clave
     *
     * [Cambiar contraseña de usuario.]
     *
     * @bodyParam  password string required Contraseña actual de usuario.
     * @bodyParam  newpassword string required Nueva contraseña de usuario.
     * @bodyParam  repassword string required Nueva contraseña repetida de usuario.
     *
     * @response {
            "message": "Se actualizo la contraseña"
        }
     *
     *
    */

   public  function changePassword(Request $request)
   {
      $user = Auth::user();
      $password = Hash::make($request->password);
      if(Hash::check($request->password, $user->password)) {
         try {
            $user->update(['password'=>$request->newpassword]);
            return $this->sendSuccess('Se actualizo su contraseña Exitosamente');
         } catch (\Throwable $th) {
            return $this->sendError('Lo sentimos, hubo un error al intentar regustrar su nueva contraseña.', 422);
         }
      } else {
         return $this->sendError('La contraseña actual no coincide con nuestros registros.', 422);
      }
   }

   public function allPermissions(){
      try {
         $permissions = Permission::all();
         $convertData = collect([]);
         foreach ($permissions as $permission) {
            $convertData->push([
               "id"    => $permission->id,
               "name"  => $permission->name
            ]);
         }
         return $this->sendResponse([ 'permissions' => $convertData ], 'Listado de Permisos');
      } catch (\Throwable $th) {
         return $this->sendError('Hubo un error al obtener los permisos', 422);
      }

   }

   public function assignPermissions(Request $request){
      try {
        $userJefe = User::where('personal_id', $request->jefe)->first();
        if(!$userJefe){
            return $this->sendError('No existe registrado el usuario Jefe del Departamento.', 422);
        }

        $userJefe->syncPermissions($request->permissions);

         return $this->sendSuccess('Permisos asignados exitosamente.');
      } catch (\Throwable $th) {
         return $this->sendError('Hubo un error al registrar los permisos => '. $th->getMessage(), 422);
      }

   }

}
