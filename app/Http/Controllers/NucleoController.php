<?php

namespace App\Http\Controllers;

use App\Repositories\NucleoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\NucleoRequest;

class NucleoController extends AppBaseController
{
    private $repository;

    public function __construct(NucleoRepository $nucleoRepository)
    {
        $this->repository = $nucleoRepository;
    }

    public function index()
    {
        try {
            $nucleos = $this->repository->all();
            $message = 'Lista de Nucleos';
            return $this->sendResponse(['nucleos' => $nucleos], $message);
        } catch (\Throwable $th) {
            return $this->sendError('Error al obtener el listado de Núcleos');
        }
    }

    public function store(NucleoRequest $request)
    {
        $codigos_separados = str_split($request->codigo);
        $data = [
            'codigo_1'              => $codigos_separados[0] ?? '',
            'codigo_2'              => $codigos_separados[1] ?? '',
            'codigo_concatenado'    => $request->codigo,
            'nombre'                => $request->nombre,
        ];
        try {
            $departamento = $this->repository->registrar($data);
            return $this->sendResponse(
                $departamento,
                'Núcleo Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Error al registrar Núcleo');
        }
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NucleoRequest $request, $id)
    {
        $codigos_separados = str_split($request->codigo);
        $data = [
            'codigo_1'              => $codigos_separados[0] ?? '',
            'codigo_2'              => $codigos_separados[1] ?? '',
            'codigo_concatenado'    => $request->codigo,
            'nombre'                => $request->nombre,
        ];
        try {
            $departamento = $this->repository->actualizar($data, $id);
            return $this->sendResponse(
                $departamento,
                'Núcleo Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Departamento'
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
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Núcleo Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Eliminar el Núcleo'
            );
        }
    }



}
