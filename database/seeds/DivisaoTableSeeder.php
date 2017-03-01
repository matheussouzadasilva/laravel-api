<?php

use Illuminate\Database\Seeder;

class DivisaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numeroRegCriados = 10;

        factory(\App\Models\Divisao::class, $numeroRegCriados)->create();
    }
}
