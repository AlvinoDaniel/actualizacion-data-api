<?php

namespace App\Console\Commands;

use App\Models\UnidadAdministrativa;
use App\Models\UnidadEjecutora;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTableUnidadAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-table-unidad-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unidadEjecJson = file_get_contents(base_path('database/json/UNIDAD_ADMIN_EJEC_NE.json'));
        $unidadEjecData = collect(json_decode($unidadEjecJson));
        $this->line('Iniciando actualizacion...');
        try {

            foreach ($unidadEjecData as $item) {
                $search = DB::table('unidades_ejecutoras')->where('codigo_unidad', $item->CODIGO_UNIDAD_EJECUTORA)->first();
                $unidadAdmin = DB::table('unidades_administrativas')->where('codigo_unidad', $item->CODIGO_UNIDAD_ADMINISTRATIVA)->first();
                if(isset($search) && isset($unidadAdmin)){
                  $unidadAdminUpdate = DB::table('unidades_administrativas')->where('id', $unidadAdmin->id)
                    ->update([
                        'id_unidad_ejec' => $search->id,
                    ]);
                  $this->line($item->CODIGO_UNIDAD_ADMINISTRATIVA. ' - '. 'UNIDAD ADMINISTRATIVA ACTUALIZADA');
                } else {
                    $this->line($item->CODIGO_UNIDAD_ADMINISTRATIVA. ' - '. 'NO SE ENCONTRO LA UNIDAD EJECUTORA O ADMINISTRATIVA');
                }
            }

            $this->info('Actualización realizada exitosamente!');

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
