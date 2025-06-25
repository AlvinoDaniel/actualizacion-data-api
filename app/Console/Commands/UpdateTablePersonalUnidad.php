<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalUnidad;

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
        $this->line('Iniciando actualizacion...');
        try {
           $personal_unidades = PersonalUnidad::select('personal_unidades.id', 'personal_unidades.codigo_unidad_admin', 'unidades_administrativas.id as id_unidad' )
            ->join('unidades_administrativas', 'personal_unidades.codigo_unidad_admin', 'unidades_administrativas.codigo_unidad_anterior')
            ->get();

            foreach ($personal_unidades as $personal) {
                $save = DB::table('personal_unidades')->where('id', $personal->id)
                    ->update(["id_unidad_admin" => $personal->id_unidad]);
                $this->line($personal->codigo_unidad_admin . ' - ' .$personal->id_unidad. ' - '.$save);
            }

            $this->info('Actualización realizada exitosamente!');

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
