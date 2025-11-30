<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "usuario" => "admin",
            "nombre" => "admin",
            "paterno" => "admin",
            "materno" => "",
            "ci" => "0",
            "ci_exp" => "",
            "grupo_san" => "",
            "sexo" => "",
            "nacionalidad" => "",
            "profesion" => "",
            "cel" => "",
            "fono" => "",
            "cel_dom" => "",
            "dir" => "",
            "latitud" => "",
            "longitud" => "",
            "correo" => "",
            "fono" => "",
            "password" => "$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC",
            "acceso" => 1,
            "tipo" => "ADMINISTRADOR",
            "fecha_registro" => date("Y-m-d"),
            "estado" => 1,
        ]);
    }
}
