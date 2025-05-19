<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Models\Personal;
use App\Models\PasswordReset;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Artisan;
use App\Mail\RecoveryPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UserController extends AppBaseController
{
    private $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Crear usuario
     *
     * [Se asigna el usuario a algún vendedor existente en el sistema.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  username required pseudonimo del usuario.
     * @bodyParam  password string required Contraseña de usuario.
     * @bodyParam  name string required Nombre del usuario.
     * @bodyParam  apellido string required Nombre del usuario.
     * @bodyParam  rol array required Nombre del rol que se desea asignar. Example: ["administrador","estandar"]
     *
     *
     *
    */

    public function store(UserRequest $request){
        $data = $request->all();
        try {
            $user = $this->repository->registrarUsuario($data);
            return $this->sendResponse(
                $user,
                'Usuario Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), $th->getCode() ?? 404);
        }
    }

    /**
     * Actualizar contraseña de usuario
     *
     * [Se actualiza la contrseña de un usuario.]
     *
     * @bodyParam  newpassword
     * @bodyParam  repassword
     *
     *
    */

    public function update_password(Request $request,$id){
        $tipo_accion = 'Actualizar Contraseña';

        try {
            $user = User::find($id);

            if(!$user){
                return $this->sendError('El rol que desea asignar al usuario no existe.');
            }

            $validator = Validator::make($request->all(), [
                'newpassword' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 422);
            }

            $user->update(['password' => $request->newpassword]);
            return $this->sendSuccess('Contraseña Actualizada exitosamente.');
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
             $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar actualizar la contraseña del usuario');
        }
    }

    public function update(UserUpdateRequest $request){
        $data = $request->all();
        try {
            $personal = $this->repository->actualizarUsuario($data);
            return $this->sendResponse(
                $personal,
                'Usuario Actualizado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Hubo un error al intentar Actualizar el Personal');
        }
    }


    public function backupDownload(){
        try {
            // Artisan::call('backup:run');
            $files = array_reverse(Storage::disk('backup')->files('cultores'));
            return Storage::disk('backup')->download($files[0]);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            return $this->sendError('Ocurrio un error al intentar crear el Respaldo');
        }
    }

    public function searchWorker(Request $request){
        try {
            $personal = $this->repository->search($request['cedula']);
            return $this->sendResponse(['personal' => $personal], '');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), $th->getCode() ?? 404);
        }
    }

    public function search_email(Request $request) {
        $cedula = $request->cedula;

        try {
            $result = $this->repository->search_user($cedula);
            return $this->sendResponse(
                $result,
                'Resultado de la Busqueda.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function sendEmailReset(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = PasswordReset::where('email', $request->email)->first();
        $personal = Personal::where('correo', $request->email)->first();
        if($user){
            Mail::to($request->email)->send(new RecoveryPassword($user->token, $personal->nombres_apellidos));
            return $this->sendResponse([], 'Correo Enviado.');
        }

        $token = Str::upper(Str::random(6));
        try {
            DB::beginTransaction();
                $reset = PasswordReset::create([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                Mail::to($request->email)->send(new RecoveryPassword($token, $personal->nombres_apellidos));
            DB::commit();
            return $this->sendResponse([], 'Correo Enviado.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function resendEmailReset(Request $request) {
        $request->validate(['email' => 'required|email']);
        try {
            $user = PasswordReset::where('email', $request->email)->first();
            $personal = Personal::where('correo', $request->email)->first();
            if($user){
                Mail::to($request->email)->send(new RecoveryPassword($user->token, $personal->nombres_apellidos));
                return $this->sendResponse([], 'Correo Enviado.');
            }
            return $this->sendResponse([], 'Correo Enviado.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'cedula'    => 'required',
            'password'  => 'required',
            'toke'      => 'require'
        ]);

        try {
            $token = PasswordReset::where('token', Str::upper($request->token))->first();
            if(!$token){
                return $this->sendError('El código de validación es incorrecto.');
            }
            $user = User::where('cedula', $request->cedula)->first();
            if(!$user){
                return $this->sendError('El funcionario no esta registrado en nuestro sistema.');
            }
            $user->password = $request->password;
            $user->save();
            $token->delete();
            return $this->sendResponse($user, 'Contraseña actualizada exitosamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }
}

