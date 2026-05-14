<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UnidadAdminRequest;
use App\Http\Requests\UnidadEjecutoraRequest;
use App\Models\UnidadAdministrativa;
use App\Repositories\UnidadAdminRepository;
use Illuminate\Http\Request;

class UnidadAdminController extends AppBaseController
{
    private $repository;

    public function __construct(UnidadAdminRepository $unidadAdminRepository)
    {
        $this->repository = $unidadAdminRepository;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->repository->allUnidadByNucleo($request);
            $message = 'Lista de Unidades Administrativas';
            return $this->sendResponse(['unidades' => $data], $message);
        } catch (\Throwable $th) {
            return $this->sendError('Error al obtener el listado de Unidades Administrativa');
        }
    }

    public function store(UnidadAdminRequest $request)
    {
        $data = [
            'codigo_unidad'         => $request->codigo,
            'descripcion'           => $request->nombre,
            'cod_nucleo'            => $request->cod_nucleo,
            'cod_unidad_padre'      => $request->cod_unidad_padre,
            'id_unidad_ejec'        => $request->id_unidad_ejec,
            'cod_escuela'           => $request->cod_escuela,
            'activo'                => isset($request->activo) ? $request->activo : 0,
        ];
        try {
            $departamento = $this->repository->registrar($data);
            return $this->sendResponse(
                $departamento,
                'Unidad Registrada exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Error al registrar Unidad: '.$th->getMessage());
        }
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnidadAdminRequest $request, $id)
    {
        $data = [
            'codigo_unidad'         => $request?->codigo,
            'descripcion'           => $request?->nombre,
            'cod_nucleo'            => $request?->cod_nucleo,
            'cod_unidad_padre'      => $request?->cod_unidad_padre,
            'id_unidad_ejec'        => $request?->id_unidad_ejec,
            'cod_escuela'           => $request?->cod_escuela,
            'activo'                => isset($request->activo) ? $request->activo : 0,
        ];
        try {
            $unidad = $this->repository->actualizar($data, $id);
            return $this->sendResponse(
                $unidad,
                'Unidad Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar la Unidad'
            );
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $unidadAdmin = UnidadAdministrativa::with('personal')->find($id);
            $hasPersonal = $unidadAdmin->personal()->count() > 0;

            if ($hasPersonal) {
                return $this->sendError("No se puede eliminar: La Unidad Administrativa '{$unidadAdmin->descripcion}' tiene un personal asociado.", 422);
            }
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Unidad Eliminada Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Eliminar la Unidad'
            );
        }
    }



}
