<?php

namespace App\Console\Commands;

use App\Models\UnidadAdministrativa;
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
        $this->line('Iniciando actualizacion...');
        try {
           $unidades = UnidadAdministrativa::select('unidades_administrativas.id', 'unidades_administrativas.cod_ejec_anterior', 'unidades_ejecutoras.id as id_ejec' )
            ->join('unidades_ejecutoras', 'unidades_administrativas.cod_ejec_anterior', 'unidades_ejecutoras.codigo_unidad')
            ->get();

            foreach ($unidades as $unidad) {
                $save = DB::table('unidades_administrativas')->where('id', $unidad->id)
                    ->update(["id_unidad_ejec" => $unidad->id_ejec]);
                $this->line($unidad->cod_ejec_anterior . ' - ' .$unidad->id_ejec.'- '.$save);
            }

            $this->info('Actualización realizada exitosamente!'.count($unidades));

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
