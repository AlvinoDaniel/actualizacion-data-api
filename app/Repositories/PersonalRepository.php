<?php

namespace App\Repositories;

use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use App\Models\PersonalMigracion;
use App\Models\PersonalUnidad;
use App\Models\Unidad;
use App\Models\UnidadAdministrativa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class PersonalRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Personal $personal)
  {
      $this->model = $personal;
  }

  /**
   * Listar todo el personal registrado de la unidad administrativa logueado
   */
  public function personalByUnidad($request){
      $jefe = Auth::user()->personal;

      if(!$request->admin){
        return [];
      }

      try {
        // 'personal_unidades.codigo_unidad_admin', 'personal_unidades.codigo_unidad_ejec',
        $personal = DB::table('personal')->select('personal.*', 'tipo_personal.descripcion as tipo_personal_descripcion', 'nucleo.nombre as nucleo_nombre', 'personal_unidades.id_unidad_admin')
            ->where('personal.cedula_identidad', '<>', Auth::user()->cedula)
            ->join('personal_unidades', function ($join) use($request, $jefe) {
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                ->where('personal_unidades.id_unidad_admin', $request->admin);
                // ->where('personal_unidades.codigo_unidad_ejec', $request->ejec);
            })
            ->leftJoin('tipo_personal', 'personal.tipo_personal', '=', 'tipo_personal.id')
            // ->leftJoin('nucleo', 'personal.cod_nucleo', '=', 'nucleo.codigo_concatenado')
            ->leftJoin('nucleo', DB::raw("SUBSTR(personal.cod_nucleo, 1,1)"), '=', 'nucleo.codigo_1')
            ->get();
        return $personal;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
  }

  public function personalRegistered($request){

      if(!isset($request->admin) || !isset($request->nucleo)){
        return [];
      }

      try {
        // 'personal_unidades.codigo_unidad_admin', 'personal_unidades.codigo_unidad_ejec'
        $personal = DB::table('personal')->select('personal.*', 'tipo_personal.descripcion as tipo_personal_descripcion', 'nucleo.nombre as nucleo_nombre')
            // ->where(DB::raw("SUBSTR(personal.cod_nucleo, 1,1)"), $request->nucleo[0])
            ->join('personal_unidades', function ($join) use($request) {
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                ->where('personal_unidades.id_unidad_admin', $request->admin);
                // ->where('personal_unidades.codigo_unidad_ejec', $request->ejec);
            })
            ->join('unidades_administrativas', function ($join) use($request){
                $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id');
                // ->where(DB::raw("SUBSTR(unidades_administrativas.cod_nucleo, 1,1)"), $request->nucleo[0]);
            })
            ->leftJoin('tipo_personal', 'personal.tipo_personal', '=', 'tipo_personal.id')
            ->leftJoin('nucleo', DB::raw("SUBSTR(unidades_administrativas.cod_nucleo, 1,1)"), '=', 'nucleo.codigo_1')
            ->get();

        $jefe = $personal->where('jefe', 1)->first();
        $personal_all = [];

        foreach ($personal as $item) {
            if($item->jefe === 0){
                $personal_all[] = $item;
            }
        }

        if($jefe){
            $userJefe = User::where('personal_id', $jefe->id)->first();
            $permissionsRegistered = $userJefe->getDirectPermissions();
            $jefe->permissions = $permissionsRegistered->map(function ($item){ return $item->name; });
        }

        return [
            "jefe"      => $jefe,
            "personal"  => $personal_all,
        ];
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
  }

  public function registrarPersonal($request){
    $departamento = PersonalUnidad::find($request['unidad']);
    $unidad_admin = $departamento->codigo_unidad_admin;
    $unidad_ejec = $departamento->codigo_unidad_ejec;
    $nucleo = Auth::user()->personal->cod_nucleo;
    $data = [
        'nombres_apellidos'   => $request[ 'nombres_apellidos'],
        'cedula_identidad'    => $request['cedula_identidad'],
        'tipo_personal'       => $request['tipo_personal'],
        'cargo_opsu'          => $request['cargo_opsu'],
        'cod_nucleo'          => $request['nucleo'],
        'correo'              => $request['correo'],
        'telefono'            => $request['telefono'],
        'pantalon'            => $request['pantalon'],
        'camisa'              => $request['camisa'],
        'zapato'              => $request['zapato'],
        'sexo'                => $request['sexo'],
        'area_trabajo'        => $request['area_trabajo'],
        'tipo_calzado'        => $request['tipo_calzado'],
        'prenda_extra'        => $request['prenda_extra'],
    ];
    try {
      DB::beginTransaction();
        $personal = Personal::create($data);
        $personal->unidades()->create([
        //   'codigo_unidad_admin' => $unidad_admin,
        //   'codigo_unidad_ejec'  => $unidad_ejec,
          'id_unidad_admin'     => $request['unidad'],
        ]);
      DB::commit();
      return $personal;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw new Exception($th->getMessage());
    }
}

  public function searchPersonal($cedula, $registered) {
    if($registered){
        $personal = Personal::where('cedula_identidad', $cedula)->get();

        if($personal->count() > 0){
            throw new Exception('El Trabajador ya está registrado.', 422);
        }
    }

    $search = PersonalMigracion::select('personal_migracion.*', 'personal.jefe', 'personal_unidades.id_unidad_admin' )
        ->where('personal_migracion.cedula_identidad', 'like', '%'. $cedula. '%')
        ->leftJoin('personal_unidades', function ($join){
            $join->on('personal_unidades.cedula_identidad', '=', 'personal_migracion.cedula_identidad');
        })
        ->leftJoin('personal', function ($join){
            $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad');
        })
        ->first();

    if(!$search){
        throw new Exception('El Trabajador no existe en nuestros registros.', 422);
    }

    return $search;
  }

  public function deletePersonal($id){
    $personal = Personal::where('id', $id)->first();

    if(!$personal){
        throw new Exception('El Personal que desea Eliminar no existe', 422);
    }

    try {
        PersonalUnidad::where('cedula_identidad', $personal->cedula_identidad)->delete();
        return $personal->delete();
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }


  }

  public function getUnidad($request){
    try {
        if(!$request->admin){
            return null;
        }

        $unidad = UnidadAdministrativa::with(['nucleo', 'unidad_ejecutora'])
            ->find($request->admin);

        if(!$unidad){
            return null;
        }

        return $unidad;
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
    }
  }

   /**
   * Listar todo el personal registrado de la unidad administrativa logueado
   */
    public function personalRegistrado($request){
        $perPage = isset($request->perPage) ? $request->perPage : 10;
        try {
        $unidades = DB::table('unidades_fisicas_ejecutoras')
                    ->select('codigo_unidad_admin', 'codigo_unidad_ejec', 'descripcion_unidad_admin', 'descripcion_unidad_ejec')
                    ->distinct('codigo_unidad_admin');

        $personal = DB::table('personal')->select('unidades_administrativas.descripcion as descripcion_unidad_admin', 'unidades_administrativas.codigo_unidad as codigo_unidad_admin', 'unidades_ejecutoras.codigo_unidad as codigo_unidad_ejec','nucleo.nombre', 'unidades_administrativas.cod_nucleo', DB::raw('count(personal.id) as personal_reg'), 'personal_unidades.id_unidad_admin')
            ->where('personal.jefe', 0)
            ->whereNotNull('personal.created_at')
            ->join('personal_unidades', function ($join) use($unidades){
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad');
                // ->joinSub($unidades, 'unidades_fisicas_ejecutoras', function ($join){
                //     $join->on('personal_unidades.codigo_unidad_admin', '=', 'unidades_fisicas_ejecutoras.codigo_unidad_admin')
                //     ->whereColumn('unidades_fisicas_ejecutoras.codigo_unidad_ejec', 'personal_unidades.codigo_unidad_ejec');
                // });
            })
            ->join('unidades_administrativas', function ($join) use($unidades){
                $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id')
               ->leftJoin('unidades_ejecutoras', 'unidades_administrativas.id_unidad_ejec', '=', 'unidades_ejecutoras.id');
            })
            // ->whereRaw('SUBSTR(personal.cod_nucleo, 1,1) = ?', [$request->nucleo])
            ->leftJoin('nucleo', DB::raw("SUBSTR(unidades_administrativas.cod_nucleo, 1,1)"), '=', 'nucleo.codigo_1')
            ->groupBy('unidades_administrativas.descripcion', 'nucleo.nombre', 'unidades_administrativas.codigo_unidad', 'unidades_ejecutoras.codigo_unidad', 'unidades_administrativas.cod_nucleo', 'personal_unidades.id_unidad_admin');

        if(isset($request->nucleo)){
            $personal->where(DB::raw("SUBSTR(unidades_administrativas.cod_nucleo, 1,1)"), $request["nucleo"]);
        }
        $data = $personal->get();
        return $data;
        } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), 421);
        }
    }

    public function personalReport($request){

        try {
        $unidades = DB::table('unidades_fisicas_ejecutoras')
                    ->select('codigo_unidad_admin', 'codigo_unidad_ejec', 'descripcion_unidad_admin', 'descripcion_unidad_ejec', 'cod_nucleo')
                    ->distinct('codigo_unidad_admin');

          $personal_all = DB::table('personal')->select('personal.*', 'unidades_administrativas.codigo_unidad as codigo_unidad_admin', 'unidades_administrativas.descripcion as descripcion_unidad_admin', 'unidades_ejecutoras.codigo_unidad as codigo_unidad_ejec', 'unidades_ejecutoras.descripcion as descripcion_unidad_ejec', 'tipo_prenda.descripcion as tipo_prenda_descripcion', 'tipo_calzado.descripcion as tipo_calzado_descripcion')
            ->where('personal.jefe', 0)
            ->join('personal_unidades', function ($join) use($unidades, $request){
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad');
                // ->joinSub($unidades, 'unidades_fisicas_ejecutoras', function ($join) use($request){
                //     $join->on('personal_unidades.codigo_unidad_admin', '=', 'unidades_fisicas_ejecutoras.codigo_unidad_admin')
                //     ->whereColumn('unidades_fisicas_ejecutoras.codigo_unidad_ejec', 'personal_unidades.codigo_unidad_ejec')
                //     ->whereRaw('SUBSTR(unidades_fisicas_ejecutoras.cod_nucleo, 1,1) = ?', [$request->nucleo]);
                // });
            })
             ->join('unidades_administrativas', function ($join) use($request){
                $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id')
                ->whereRaw('SUBSTR(unidades_administrativas.cod_nucleo, 1,1) = ?', [$request->nucleo])
               ->leftJoin('unidades_ejecutoras', 'unidades_administrativas.id_unidad_ejec', '=', 'unidades_ejecutoras.id');
            })
            ->join('tipo_prenda', 'personal.prenda_extra', 'tipo_prenda.id')
            ->join('tipo_calzado', 'personal.tipo_calzado', 'tipo_calzado.id')
            ->get();


        $jefes = DB::table('personal')->select('personal.*',  'unidades_administrativas.codigo_unidad as codigo_unidad_admin', 'unidades_administrativas.descripcion as descripcion_unidad_admin', 'unidades_ejecutoras.codigo_unidad as codigo_unidad_ejec', 'unidades_ejecutoras.descripcion as descripcion_unidad_ejec', 'tipo_prenda.descripcion as tipo_prenda_descripcion', 'tipo_calzado.descripcion as tipo_calzado_descripcion')
            ->where('personal.jefe', 1)
            // ->whereRaw('SUBSTR(personal.cod_nucleo, 1,1) = ?', [$request->nucleo])
            ->join('users', 'personal.cedula_identidad', 'users.cedula')
            ->join('tipo_prenda', 'personal.prenda_extra', 'tipo_prenda.id')
            ->join('tipo_calzado', 'personal.tipo_calzado', 'tipo_calzado.id')
            ->join('personal_unidades', function ($join) use($unidades, $request){
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad');
            })
            ->join('unidades_administrativas', function ($join) use($request){
                $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id')
                ->whereRaw('SUBSTR(unidades_administrativas.cod_nucleo, 1,1) = ?', [$request->nucleo])
               ->leftJoin('unidades_ejecutoras', 'unidades_administrativas.id_unidad_ejec', '=', 'unidades_ejecutoras.id');
            })
            ->get();
        //   $jefes = DB::table('personal')->select('personal.cedula_identidad', 'personal.nombres_apellidos', 'personal.jefe', 'personal.correo', 'personal.telefono', 'unidades_fisicas_ejecutoras.descripcion_unidad_admin', 'unidades_fisicas_ejecutoras.codigo_unidad_admin')
        //   ->where('jefe', 1)
        //   ->whereNotIn('personal.cedula_identidad', function($query){
        //         $query->select('cedula')
        //         ->from('users');
        //     })
        //   ->where('personal.cedula_identidad', 11)
        //   ->join('personal_unidades', function ($join) use($unidades){
        //         $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
        //         ->joinSub($unidades, 'unidades_fisicas_ejecutoras', function ($join){
        //             $join->on('personal_unidades.codigo_unidad_admin', '=', 'unidades_fisicas_ejecutoras.codigo_unidad_admin')
        //             ->whereColumn('unidades_fisicas_ejecutoras.codigo_unidad_ejec', 'personal_unidades.codigo_unidad_ejec');
        //         });
        //     })
        //   ->get();

          return [
              "jefes"       => $jefes,
              "personal"    => $personal_all,
          ];
        } catch (\Throwable $th) {
          throw new Exception($th->getMessage());
        }
    }

    public function getUnidsWithoutBoss(){
        try {
            $unids = UnidadAdministrativa::where('jefe', 0)->get();
            return $unids;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function personalByUnidWithoutBoss($request){
      try {
        // 'personal_unidades.codigo_unidad_admin', 'personal_unidades.codigo_unidad_ejec',
        $personal = DB::table('personal')->select('personal.*', 'tipo_personal.descripcion as tipo_personal_descripcion', 'nucleo.nombre as nucleo_nombre', 'personal_unidades.id_unidad_admin', 'unidades_administrativas.descripcion as descripcion_unidad')
            ->where('personal.jefe', 0)
            ->join('personal_unidades', function ($join) {
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad');
            })
            ->join('unidades_administrativas', function ($join) use($request){
                $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id')
                ->where('unidades_administrativas.jefe', 0)
                ->where('unidades_administrativas.cod_nucleo', $request->nucleo)
               ->leftJoin('unidades_ejecutoras', 'unidades_administrativas.id_unidad_ejec', '=', 'unidades_ejecutoras.id');
            })
            ->leftJoin('tipo_personal', 'personal.tipo_personal', '=', 'tipo_personal.id')
            ->leftJoin('nucleo', DB::raw("SUBSTR(personal.cod_nucleo, 1,1)"), '=', 'nucleo.codigo_1')
            ->get();
        return $personal;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
    }

    /**
   * Listar todo los Jefes registrado
   */
    public function jefesRegistrado($request){
        try {
            $personal = DB::table('unidades_administrativas')->select('personal.*','unidades_administrativas.descripcion as descripcion_unidad_admin', 'unidades_administrativas.codigo_unidad as codigo_unidad_admin','nucleo.nombre', 'unidades_administrativas.cod_nucleo', 'personal_unidades.id_unidad_admin', 'personal_unidades.id as id_personal_unidad')
                ->leftJoin('personal_unidades', function ($join){
                    $join->on('personal_unidades.id_unidad_admin', '=', 'unidades_administrativas.id')
                    ->join('personal', function ($join){
                        $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                        ->where('personal.jefe', 1);
                    });
                })
                ->leftJoin('nucleo', 'unidades_administrativas.cod_nucleo', '=', 'nucleo.codigo_concatenado');

            if(isset($request->nucleo)){
                $personal->where('unidades_administrativas.cod_nucleo', $request["nucleo"]);
            }
            $data = $personal->get();
            return $data;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 421);
        }
    }
    /**
   * ACTUALIZAR JEFATURA
   */
    public function actualizarJefatura($request){
        try {
            $jefeActual = DB::table('personal_unidades')->select('personal.*')
                ->where('personal_unidades.id_unidad_admin', $request['id_unidad_admin'])
                ->join('personal', function ($join){
                    $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                    ->where('personal.jefe', 1);
                })->first();

            if($jefeActual->cedula_identidad === $request['cedula_identidad']){
                $updateJefeActual = Personal::where('cedula_identidad', $jefeActual->cedula_identidad)
                    ->update(["id_cargo"  => $request['id_cargo']]);
                 return $updateJefeActual;
            } else {
                $updateJefeActual = Personal::where('cedula_identidad', $jefeActual->cedula_identidad)
                    ->update(["id_cargo"  => null, "jefe"   => 0]);
            }

            $jefeNuevo = Personal::where('cedula_identidad', $request['cedula_identidad'])->first();

            if(!$jefeNuevo){
                $personal = PersonalMigracion::where('cedula_identidad', $request['cedula_identidad'])->first();

                $nuevoPersonal = Personal::nuevoPersonal([
                    'nombres_apellidos'   => $personal->nombres,
                    'cedula_identidad'    => $personal->cedula_identidad,
                    'tipo_personal'       => $personal->tipo_personal,
                    'cargo_opsu'          => $personal->cargo_opsu,
                    'cod_nucleo'          => $personal->cod_nucleo,
                    'correo'              => $personal->correo,
                    'telefono'            => $personal->telefono,
                    'sexo'                => $personal->sexo,
                    'jefe'                => 1,
                    'id_cargo'            => $request['id_cargo'],
                ]);

                $nuevoPersonal->unidades()->create([
                    'id_unidad_admin'     => $request['id_unidad_admin'],
                ]);

                return $nuevoPersonal;
            }

            $jefeNuevo->update([
                "id_cargo"  => $request['id_cargo'],
                "jefe"      => 1,
            ]);

            $jefeNuevo->unidades()->update([
                'id_unidad_admin'     => $request['id_unidad_admin'],
            ]);

            return $jefeNuevo;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 421);
        }
    }

}
