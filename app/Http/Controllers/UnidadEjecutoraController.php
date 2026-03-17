<?php

namespace App\Http\Controllers;

use App\Repositories\UnidadEjecutoraRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UnidadEjecutoraRequest;
use App\Models\UnidadAdministrativa;
use App\Models\UnidadEjecutora;

class UnidadEjecutoraController extends AppBaseController
{
    private $repository;

    public function __construct(UnidadEjecutoraRepository $unidadEjecutoraRepository)
    {
        $this->repository = $unidadEjecutoraRepository;
    }

    public function index()
    {
        try {
            $data = $this->repository->all();
            $message = 'Lista de Unidades ejecutoras';
            return $this->sendResponse(['unidades' => $data], $message);
        } catch (\Throwable $th) {
            return $this->sendError('Error al obtener el listado de Unidades Ejecutora');
        }
    }

    public function store(UnidadEjecutoraRequest $request)
    {
        $data = [
            'codigo_unidad'    => $request->codigo,
            'descripcion'      => $request->nombre,
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
    public function update(UnidadEjecutoraRequest $request, $id)
    {
        $data = [
            'año'              => $request?->anio ?? null,
            'codigo_unidad'    => $request->codigo,
            'descripcion'      => $request->nombre,
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
            $unidadAdmin = UnidadAdministrativa::where('id_unidad_ejec', $id)->first();

            // Verificamos si existe la relación
            // Como es hasOne, si existe, el atributo 'unidadAdmin' no será null
            if ($unidadAdmin) {
                return $this->sendError("No se puede eliminar: La Unidad Ejecutora '{$unidadAdmin?->unidad_ejecutora?->descripcion}' esta asociado a la unidad administrativa ".$unidadAdmin?->descripcion, 422);
            }
            // $this->repository->delete($id);
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
