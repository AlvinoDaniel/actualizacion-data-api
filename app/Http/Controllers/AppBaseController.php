<?php

namespace App\Http\Controllers;

use Response;
use Carbon\Carbon;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($data, $message)
    {
        return Response::json([
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
        ], 200);
    }

    public function sendError($error, $code = 404, $data = null)
    {
        $dataResponse = [
            'success'   => false,
            'errors' => [
                'message'   => $error,
                'code_error'=> $code
            ]
        ];

        if($data){
            $dataResponse['data'] = $data;
        }

        return Response::json($dataResponse, $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }


    public function generateLog($codigo, $message, $tipo, $status)
    {
        $user = Auth::user();
        if(!$user)
        {
            $usuario = 'Usuario no Logueado';
        } 
        else 
        {
            $usuario = $user->email.'/'.$user->username;
        }
        $fecha = Carbon::now();
        $newLog = Log::create([
            'codigo'    => $codigo,
            'mensaje'   => $message,
            'usuario'   => $usuario, 
            'ip'        => \Request::ip(),
            'tipo'      => $tipo,
            'status'    => $status,
            'fecha_evento' => $fecha
        ]);
    }



    public function getBase64($file, $clave,$path_corto)
    {
        //se obtiene el tipo de archivo
        $data = explode('/', mime_content_type($file));
        preg_match("/data:".$data[0]."\/(.*?);/", $file, $file_extension); // extract the image extension
        $file = preg_replace('/data:'.$data[0].'\/(.*?);base64,/', '', $file); // remove the type part
        $file = str_replace(' ', '+', $file);

        //se comprueba si es un tipo imagen y se le asigna la extension correspondiente
        if ($data[0] === 'image') {
            $fileName = $clave.'.'.$file_extension[1];//.$file_extension[1]; //generating unique file name;
        }
        $path = $path_corto. $fileName;
        Storage::disk('public')->put('imagen_parametro/'.$fileName, base64_decode($file));
        return $path;
    }
}
