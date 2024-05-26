<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project1 = App\Project::firstOrCreate(['name' => 'Project 1', 'client_id' => 1]);
        $project2 = App\Project::firstOrCreate(['name' => 'Project 2', 'client_id' => 1]);
    }
}
