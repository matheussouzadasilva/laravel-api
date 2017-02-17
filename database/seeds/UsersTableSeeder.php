<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
        	'name'		=> 'Matheus Silva',
        	'email'		=> 'asdjakmat2sdj@gmail.com',
        	'password'	=> bcrypt('123456'),
        ]);
    }
}
