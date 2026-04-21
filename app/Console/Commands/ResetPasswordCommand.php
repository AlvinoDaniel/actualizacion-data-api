<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-password-command {cedula}';

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
        $cedula = $this->argument('cedula');
        if(!isset($cedula)){
            $this->error("Ingrese el nro de cedula del trabajador");
            return;
        }

        $user = User::where('cedula', $cedula)->first();

        if(!$user){
            $this->error("La cedula de identidad no existe como jefe en nuestros registros.");
            return;
        }

        $user->update(['password' => $cedula]);
        $this->info("Contraseña Reseteada. Nueva Contraseña: ".$cedula);
    }
}
