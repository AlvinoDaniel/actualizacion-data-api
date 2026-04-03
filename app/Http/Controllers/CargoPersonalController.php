<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CargoPersonalRequest;
use App\Repositories\CargoPersonalRepository;
use App\Models\CargoPersonal;

class CargoPersonalController extends AppBaseController
{
    private $repository;

    public function __construct(CargoPersonalRepository $cargoPersonalRepository)
    {
        $this->repository = $cargoPersonalRepository;
    }

    public function index()
    {
        try {
            $cargos = $this->repository->all();
            return $this->sendResponse(['cargos_personal' => $cargos], 'Lista de cargos del personal');
        } catch (\Throwable $th) {
            return $this->sendError('Error al obtener cargos de personal: ' . $th->getMessage());
        }
    }

    public function store(CargoPersonalRequest $request)
    {
        $data = [
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
        ];

        try {
            $cargo = $this->repository->registrar($data);
            return $this->sendResponse($cargo, 'Cargo de personal registrado exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError('Error al registrar cargo de personal: ' . $th->getMessage());
        }
    }

    public function update(CargoPersonalRequest $request, $id)
    {
        $data = [
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
        ];

        try {
            $cargo = $this->repository->actualizar($data, $id);
            return $this->sendResponse($cargo, 'Cargo de personal actualizado exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar actualizar el cargo de personal'
            );
        }
    }

    public function destroy($id)
    {
        try {
            $cargo = CargoPersonal::with('personal')->find($id);

            if (!$cargo) {
                return $this->sendError('Cargo de personal no encontrado', 404);
            }

            if ($cargo->personal()->count() > 0) {
                return $this->sendError('No se puede eliminar: el cargo está asociado a uno o más personal.', 422);
            }

            $this->repository->delete($id);
            return $this->sendSuccess('Cargo de personal eliminado exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar eliminar el cargo de personal'
            );
        }
    }
}
