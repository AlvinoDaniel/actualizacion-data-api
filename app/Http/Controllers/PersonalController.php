<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PersonalRepository;
use App\Http\Requests\PersonalRequest;
use Carbon\Carbon;
use App\Exports\ReporteAllExport;
use App\Models\PersonalUnidad;
use Maatwebsite\Excel\Facades\Excel;

class PersonalController extends AppBaseController
{
    private $repository;

    public function __construct(PersonalRepository $personalRepository)
    {
        $this->repository = $personalRepository;
        $this->middleware('verifiedBoss');
    }

    /**
     * Listar todo el Personal.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $personal = $this->repository->personalByUnidad($request);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse(['personal' => $personal], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalRequest $request)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->registrarPersonal($data);
            return $this->sendResponse(
                $personal,
                'Personal Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonalRequest $request, $id)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->actualizar($data, $id);
            $unidad = $personal?->unidades[0]?->id_unidad_admin;
            if(isset($request["multiple"]) && $request["multiple"] === true && $data["unidad"] !== $unidad){
                PersonalUnidad::where('id', $personal?->unidades[0]?->id)->update([
                    "id_unidad_admin"   => $data["unidad"]
                ]);
            }
            return $this->sendResponse(
                $personal,
                'Personal Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Personal'
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
            $this->repository->deletePersonal($id);
            return $this->sendSuccess(
                'Personal Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function search(Request $request) {
        $cedula = $request->cedula;
        $registered = filter_var($request->registered, FILTER_VALIDATE_BOOLEAN);

        try {
            $result = $this->repository->searchPersonal($cedula, $registered);
            return $this->sendResponse(
                $result,
                'Resultado de la Busqueda.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function genareteList(Request $request){
        try {
            $personal = $this->repository->personalByUnidad($request);
            $unidad = $this->repository->getUnidad($request);

            if(!$unidad){
                return $this->sendError('Unidad Administrativa no existe.');
            }

            $pdf = \PDF::loadView('pdf.personal_registrado', [
               'personal'           => $personal,
                'unidad_admin'      => $unidad->descripcion ?? '',
                'unidad_ejec'       => $unidad?->unidad_ejecutora?->descripcion ?? '',
                'nucleo'            => $unidad->nucleo->nombre ?? '',
                'fecha'             => Carbon::now()->format('d/m/Y'),
            ]);
            return $pdf->download('Personal_registrado.pdf');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }
    public function genareteReport(Request $request){
        try {
            $personal = $this->repository->personalRegistrado($request);

            if(!isset($request->download)){
                return $this->sendResponse(
                    $personal,
                    'Resultado de la Busqueda.'
                );
            }

            $pdf = \PDF::loadView('pdf.personal_by_nucleo', [
               'personal'           => $personal,
                'fecha'             => Carbon::now()->format('d/m/Y'),
            ]);
            return $pdf->download('Personal_by_nucleo.pdf');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

     /**
     * Listar todo el Personal Por Undidad.
     *
     * @return \Illuminate\Http\Response
     */
    public function personalAllUnidad(Request $request)
    {
        try {
            $personal = $this->repository->personalRegistered($request);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse($personal, $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function exportAllPersonal(Request $request){

        try {
         $data = $this->repository->personalReport($request);
        //  return $this->sendResponse($data["personal"], '');

         $nameFile =  'REPORTE_PERSONAL_'.Carbon::now()->format('dmy').'.xlsx';

         return Excel::download(new ReporteAllExport($data["personal"], $data["jefes"]), $nameFile);
        //  return $this->sendResponse($data, '');

        } catch (\Throwable $th) {
           if($th->getCode() === 422){
             return $this->sendError($th->getMessage(), $th->getCode());
           }
           return $this->sendError("Error al obtener los datos del Cliente.".$th->getMessage(), 420);
        }

    }

     /**
     * Listar todo el Personal Sin Jefe.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPersonalLagging(Request $request)
    {
        try {
            $personal = $this->repository->personalByUnidWithoutBoss($request);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse(['personal' => $personal], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * Listar todo el Personal Sin Jefe.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnidsWithoutLeadership()
    {
        try {
            $unids = $this->repository->getUnidsWithoutBoss();
            $message = 'Lista de Unidades';
            return $this->sendResponse(['unidades' => $unids], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

     /**
     * Listar todo los Jefes Registrados.
     *
     * @return \Illuminate\Http\Response
     */
    public function bossAllNucleo(Request $request)
    {
        try {
            $boss = $this->repository->jefesRegistrado($request);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse($boss, $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

     /**
     * Listar todo los Jefes Registrados.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateBoss(Request $request)
    {
        try {
            $boss = $this->repository->actualizarJefatura($request);
            $message = 'Actualizacion exitosa';
            return $this->sendResponse($boss, $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Importar personal masivo desde CSV
     */
    public function importarMasivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:20480', // 20MB max
        ]);

        try {
            $resultado = $this->repository->importarPersonalMasivo($request->file('archivo'));
            return $this->sendResponse($resultado, 'Importación completada exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError('Error en la importación: ' . $th->getMessage(), $th->getCode() ?: 500);
        }
    }

    /**
     * Obtener personal por cédula
     */
    public function getByCedula($cedula)
    {
        try {
            $personal = $this->repository->getPersonalByCedula($cedula);
            return $this->sendResponse($personal, 'Personal encontrado.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), $th->getCode() ?: 404);
        }
    }

    /**
     * Exportar plantilla CSV para importación masiva
     */
    public function exportarPlantillaImportacion()
    {
        try {
            $content = $this->repository->exportarPlantillaImportacion();
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="plantilla_importacion.csv"',
            ]);
        } catch (\Throwable $th) {
            return $this->sendError('Error al exportar la plantilla: ' . $th->getMessage(), $th->getCode() ?: 500);
        }
    }

    /** Actualizar unidad administrativa asociada a un personal */
    public function actualizarUnidadPersonal(Request $request, $id)
    {
        $request->validate([
            'unidad_admin_id' => 'required|exists:unidades_administrativas,id',
        ]);

        try {
            $personal = $this->repository->actualizarUnidadPersonal($id, $request->unidad_admin_id);
            return $this->sendResponse($personal, 'Unidad administrativa actualizada exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError('Error al actualizar la unidad administrativa: ' . $th->getMessage(), $th->getCode() ?: 500);
        }
    }

}
