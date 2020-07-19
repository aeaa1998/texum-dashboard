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
        Worker::truncate();
        factory(Worker::class, 40)->create();
        //
    }
}
