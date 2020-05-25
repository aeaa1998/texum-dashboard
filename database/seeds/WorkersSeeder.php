<?php

use App\Models\Worker;
use Illuminate\Database\Seeder;

class WorkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Worker::class, 20)->create();
        //
    }
}
