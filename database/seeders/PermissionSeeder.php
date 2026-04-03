<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permission::create(['name' => 'reporte-general']);
        // Permission::create(['name' => 'registrar-personal-rezagado']);
        Permission::create(['name' => 'gestionar-jefe']);

        $superAdmin = Role::create(['name' => 'Super Admin']);

        $userAdmin = User::where('cedula', 12659389)->first();

        if($userAdmin){
            $userAdmin->assignRole($superAdmin);
        }
    }
}
