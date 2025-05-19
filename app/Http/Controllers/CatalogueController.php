<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;

class CatalogueController extends AppBaseController
{
    protected $class;

    protected $table;

    protected $model;

    protected $models = [
        'NUCLEO'            => ['model' => \App\Models\Nucleo::class],
        'TIPO_PERSONAL'     => ['model' => \App\Models\TipoPersonal::class],
        'TIPO_PRENDA'       => ['model' => \App\Models\TipoPrenda::class],
        'TIPO_CALZADO'      => ['model' => \App\Models\TipoCalzado::class],
        'AREA_TRABAJO'      => ['model' => \App\Models\AreaTrabajo::class],
    ];

    public function __construct(Request $request)
    {
        $this->table = str_replace('-', '_', strtoupper($request->table));

        if(Arr::has($this->models, $this->table)){
            $this->model = $this->models[$this->table];
            $this->class = new $this->model['model']() ?? null;
        }
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if(!Arr::has($this->models, $this->table)){
            return $this->sendError('La tabla seleccionada no existe.');
        }
        $query = $this->class::query();

        try {
            $data = $query->get();
            return $this->sendResponse($data, 'Datos Obtenidos');
        } catch (\Throwable $th) {
            return $this->sendError('Errror al obtener los datos.');
        }
    }
}
