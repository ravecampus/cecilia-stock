<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('leave_type')->delete();
        DB::table('leave_type')->insert([
            'id' => 1,
            'description'=>'Sick Leave', 
            'number_of_days'=> 5, 
            'usable'=> 1, 
        ]);
        DB::table('leave_type')->insert([
            'id' => 2,
            'description'=>'Vacation Leave', 
            'number_of_days'=> 5, 
            'usable'=> 1, 
        ]);
        DB::table('leave_type')->insert([
            'id' => 3,
            'description'=>'Emergency', 
            'number_of_days'=> 5, 
            'usable'=> 1, 
        ]);

        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'role'=>1, 
            'first_name'=>'winsdev', 
            'last_name'=>'2019', 
            'username'=>'admin',
            // 'email'=>'admin@gmail.com',
            'password'=>bcrypt('admin')
        ]);
    }
}
