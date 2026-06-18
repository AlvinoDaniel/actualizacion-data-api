<?php

namespace App\Console\Commands;

use App\Models\UnidadAdministrativa;
use App\Models\UnidadEjecutora;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateNucleoPersonalmigracion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-nucleo-personal-migracion {nucleo}';

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
        $nucleo = $this->argument('nucleo');
        $personal = file_get_contents(base_path('database/json/PERSONAL_MIGRACION.json'));
        $personalData = collect(json_decode($personal));
        $this->line('Iniciando actualizacion...');
        try {

            foreach ($personalData as $item) {
                $search = DB::table('personal_migracion')->where('cedula_identidad', $item->cedula)->first();
                if(isset($search)){
                  $unidadAdminUpdate = DB::table('personal_migracion')->where('id', $search->id)
                    ->update([
                        'cod_nucleo' => $nucleo,
                    ]);
                  $this->line($item->cedula. ' - '. 'ACTUALIZADO');
                } else {
                    $this->line($item->cedula. ' - '. 'NO SE ENCONTRO FUNCIONARIO');
                }
            }

            $this->info('Actualización realizada exitosamente!');

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
