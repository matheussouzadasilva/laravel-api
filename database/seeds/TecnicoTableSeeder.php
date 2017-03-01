<?php

use Illuminate\Database\Seeder;

class TecnicoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numeroRegCriados = 10;

        factory(\App\Models\Tecnico::class, $numeroRegCriados)->create();
    }
}
