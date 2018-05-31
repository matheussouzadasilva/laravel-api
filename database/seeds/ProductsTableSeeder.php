<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$numeroRegCriados = 10;

        factory(\App\Models\Product::class, $numeroRegCriados)->create();
    }
}
