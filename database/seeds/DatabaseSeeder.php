<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriasTableSeeder::class);
        $this->call(DivisaoTableSeeder::class);
        $this->call(TecnicoTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
    }
}
