<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalUnidad;
use App\Models\UnidadAdministrativa;

class UpdateTablePersonalUnidad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-table-personal-unidad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar tabla Personal Unidades con los ID de la nueva tabla';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unidadAdminJson = file_get_contents(base_path('database/json/UNIDAD_ADMINISTRATIVAS_BOLIVAR.json'));
        $unidadAdmincData = collect(json_decode($unidadAdminJson));
        $this->line('Iniciando actualizacion...');
        try {
            //    $jefe_unidades =  DB::table('jefes_unidades')->select('jefes_unidades.cedula', 'jefes_unidades.cod_unidad_admin', 'unidades_administrativas.id as id_unidad' )
            //     ->join('unidades_administrativas', 'jefes_unidades.cod_unidad_admin', 'unidades_administrativas.codigo_unidad')
            //     ->get();
            //    $personal_unidades = PersonalUnidad::select('personal_unidades.id', 'personal_unidades.codigo_unidad_admin', 'unidades_administrativas.id as id_unidad' )
            //     ->join('unidades_administrativas', 'personal_unidades.codigo_unidad_admin', 'unidades_administrativas.codigo_unidad_anterior')
            //     ->get();

            // foreach ($personal_unidades as $personal) {
            //     $save = DB::table('personal_unidades')->where('id', $personal->id)
            //         ->update(["id_unidad_admin" => $personal->id_unidad]);
            //     $this->line($personal->codigo_unidad_admin . ' - ' .$personal->id_unidad. ' - '.$save);
            // }
            // foreach ($jefe_unidades as $personal) {
            //     $save = DB::table('personal_unidades')->where('cedula_identidad', $personal->cedula)->get();

            //     if(count($save) === 0){
            //         $insert = DB::table('personal_unidades')->insert([
            //             "cedula_identidad"  => $personal->cedula,
            //             "id_unidad_admin"   => $personal->id_unidad
            //         ]);
            //         $this->line($personal->cod_unidad_admin . ' - ' .$personal->id_unidad. ' - '.$personal->cedula. ' - insert:'. $insert);
            //     } else {
            //         $this->line($personal->cod_unidad_admin . ' - ' .$personal->id_unidad. ' - '.$personal->cedula);
            //     }
            //         // ->update(["id_unidad_admin" => $personal->id_unidad]);
            // }

            $count = 0;
             foreach ($unidadAdmincData as $item) {
                $search = DB::table('personal')->where('cedula_identidad', $item->cedula_responsable)->first();
                $unid_ejc = DB::table('unidades_ejecutoras')->where('codigo_unidad', $item->codigo_unidad_ejecutora)->first();
                $unid_admin = DB::table('unidades_administrativas')->where('codigo_unidad', $item->codigo_unidad_administrativa)->first();
                if(!$unid_admin){
                    $unidad =UnidadAdministrativa::create([
                        "cod_nucleo"            => $item->cod_nucleo,
                        "codigo_unidad"         => $item->codigo_unidad_administrativa,
                        "descripcion"           => $item->nombre,
                        "correo_dependencia"    => $item->correo,
                        "activo"                => $item->activo,
                        "id_unidad_ejec"        => $unid_ejc ? $unid_ejc->id : null,
                        "cod_escuela"           => $item->escuela,
                        "cod_unidad_padre"      => $item->depende_de,
                        "año"                   => $item->año,
                        "jefe"                  => $item->cedula_responsable !== "" ? 1 : 0
                    ]);
                    if($search){
                        $insert_personal =DB::table('personal_unidades')->insert([
                            "cedula_identidad"        => $item->cedula_responsable,
                            "id_unidad_admin"         => $unidad->id,
                        ]);
                        $this->line($item->cedula_responsable. ' - '. $insert_personal);
                    } else {
                         $this->line($item->cedula_responsable. ' - NO ENCONTRADO');
                    }

                     $this->line($unidad->descripcion. ' - '. $unidad->id);
                }
                $count++;
                // if(!$unid_admin){
                //     $this->line($item->nombre.':'.$item->codigo_unidad_administrativa);
                // }
            }

            $this->info('Actualización realizada exitosamente! cant: '.$count);

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
