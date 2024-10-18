<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // //TRUNCATE
        // Schema::disableForeignKeyConstraints();
        // User::truncate();
        // Schema::enableForeignKeyConstraints();
        

        // User::insert([
        //     "name"=>"Agus",
        //     "email"=>"aguskalehe@gmail.com",
        //     "password"=>Hash::make("AGUS123"),
        //     "role_id"=>4
        // ]);
    }
}