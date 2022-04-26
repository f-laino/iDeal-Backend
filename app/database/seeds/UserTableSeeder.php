<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'name' => 'admin',
            'email' => 'it@carplanner.com',
            'password' => bcrypt('admin'),
        ]);

        $user2 = User::create([
            'name' => 'marta',
            'email' => 'm.daina@carplanner.com',
            'password' => bcrypt('marta'),
        ]);


    }
}
