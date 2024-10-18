<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // //TRUNCATE
        // Schema::disableForeignKeyConstraints();
        // Role::truncate();
        // Schema::enableForeignKeyConstraints();

        // $datas =[
        //     ["name"=>"waitress"],
        //     ["name"=>"chef"],
        //     ["name"=>"cashier"],
        //     ["name"=>"manager"],
        // ];
        // foreach ($datas as $data) {
        //     Role::insert([
        //         "name"=> $data['name'],
        //         "created_at"=>Carbon::now(),
        //         "updated_at"=>Carbon::now(),
        //     ]);
        // }
        
    }
}