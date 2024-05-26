<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estimation1 = App\Estimation::firstOrCreate(['project_id' => 1, 'type' => 'hourly'], ['amount' => 100.00]);
        $estimation2 = App\Estimation::firstOrCreate(['project_id' => 2, 'type' => 'fixed'], ['amount' => 500.00]);
    }
}
