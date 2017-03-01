<?php

use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$numeroRegCriados = 10;

        factory(\App\Models\Categoria::class, $numeroRegCriados)->create();
    }
}
