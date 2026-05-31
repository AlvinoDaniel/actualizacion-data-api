<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalUnidad;
use App\Models\Personal;
use App\Models\UnidadAdministrativa;

class UpdateTablePersonalUnidad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-table-personal-unidad {file}';

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
        $file = $this->argument('file');
        $unidadAdminJson = file_get_contents(base_path('database/json/'. $file .'.json'));
        $unidadAdmincData = collect(json_decode($unidadAdminJson));
        $this->line('Iniciando actualizacion...');
        try {
            $count = 0;
             foreach ($unidadAdmincData as $item) {

                if(empty($item->CEDULA_RESPONSABLE) || empty($item->CODIGO_UNIDAD_ADMINISTRATIVA)){
                    $this->line('Registro con datos incompletos: CEDULA_RESPONSABLE o CODIGO_UNIDAD_ADMINISTRATIVA vacíos');
                    continue;
                }
                $search = DB::table('personal')->where('cedula_identidad', $item->CEDULA_RESPONSABLE)->first();
                $migracion = DB::table('personal_migracion')->where('cedula_identidad', $item->CEDULA_RESPONSABLE)->first();
                $unid_admin = DB::table('unidades_administrativas')->where('codigo_unidad', $item->CODIGO_UNIDAD_ADMINISTRATIVA)->first();
                $personal_nuevo = null;
                if(!isset($unid_admin)){
                    $this->line($item->CODIGO_UNIDAD_ADMINISTRATIVA. ' - NO EXISTE');
                    continue;
                }

                if(!isset($search) && !isset($migracion)){
                    $this->line($item->CEDULA_RESPONSABLE. ' - NO EXISTE EL TRABAJADOR');
                    continue;
                }

                 if(!isset($search) && isset($migracion)){
                    $personal_nuevo = Personal::create([
                        'nombres_apellidos' => $migracion->nombres,
                        'cedula_identidad' => $migracion->cedula_identidad,
                        'tipo_personal' => $migracion?->tipo_personal ?? null,
                        'cargo_opsu' => $migracion?->cargo ?? null,
                        'cod_nucleo' => $migracion->cod_nucleo,
                        'jefe' => 1,
                        'correo' => $migracion?->correo ?? null,
                        'telefono' => $migracion?->telefono ?? null,
                        'sexo' => $migracion?->sexo ?? null,
                    ]);

                    $insert_personal =DB::table('personal_unidades')->insert([
                        "cedula_identidad"        => $item->CEDULA_RESPONSABLE,
                        "id_unidad_admin"         => $unid_admin->id,
                    ]);
                    $this->line($item->CEDULA_RESPONSABLE. ' - '. $insert_personal);
                    $this->line($item->CEDULA_RESPONSABLE. ' - '. 'PERSONAL CREADO');
                    DB::table('unidades_administrativas')->where("id", $unid_admin->id)
                     ->update(["jefe" => 1]);
                    continue;
                 }

                 $existeVinculacion = DB::table('personal_unidades')->where('cedula_identidad', $item->CEDULA_RESPONSABLE)->first();
                 if(isset($existeVinculacion)){
                     DB::table('personal_unidades')->where('cedula_identidad', $item->CEDULA_RESPONSABLE)
                     ->update(["id_unidad_admin" => $unid_admin->id]);
                    DB::table('unidades_administrativas')->where("id", $unid_admin->id)
                     ->update(["jefe" => 1]);
                    $this->line($item->CEDULA_RESPONSABLE. ' - VINCULACION ACTUALIZADA');
                    continue;
                 } else {
                    $insert_personal =DB::table('personal_unidades')->insert([
                        "cedula_identidad"        => $item->CEDULA_RESPONSABLE,
                        "id_unidad_admin"         => $unid_admin->id,
                    ]);
                    DB::table('unidades_administrativas')->where("id", $unid_admin->id)
                     ->update(["jefe" => 1]);
                    $this->line($item->CEDULA_RESPONSABLE. ' - '. 'VINCULACION CREADA');
                 }

                $this->line($unid_admin->descripcion. ' - '. $unid_admin->id);
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
