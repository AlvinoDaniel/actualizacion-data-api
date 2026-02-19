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
        $unidadEjecJson = file_get_contents(base_path('database/json/UNIDAD_EJEC_BOLIVAR.json'));
        $unidadEjecData = collect(json_decode($unidadEjecJson));
        $this->line('Iniciando actualizacion...');
        try {
        //    $unidades = UnidadAdministrativa::select('unidades_administrativas.id', 'unidades_administrativas.cod_ejec_anterior', 'unidades_ejecutoras.id as id_ejec' )
        //     ->join('unidades_ejecutoras', 'unidades_administrativas.cod_ejec_anterior', 'unidades_ejecutoras.codigo_unidad')
        //     ->get();

            foreach ($unidadEjecData as $item) {
                $search = DB::table('unidades_ejecutoras')->where('codigo_unidad', $item->codigo_unidad)->first();
                if(!$search){
                    $insert =DB::table('unidades_ejecutoras')->insert([
                        "codigo_unidad"     => $item->codigo_unidad,
                        "descripcion"       => $item->descripcion,
                        "año"               => $item->año
                    ]);
                    $this->line($item->codigo_unidad. ' - ' . $insert);
                }
            }

            $this->info('Actualización realizada exitosamente!');

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
