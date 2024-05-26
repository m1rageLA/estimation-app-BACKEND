<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client1 = App\Client::firstOrCreate(['email' => 'client1@example.com'], ['name' => 'Client 1']);
        $client2 = App\Client::firstOrCreate(['email' => 'client2@example.com'], ['name' => 'Client 2']);
        $client3 = App\Client::firstOrCreate(['email' => 'client3@example.com'], ['name' => 'Client 3']);
        $client4 = App\Client::firstOrCreate(['email' => 'client5@example.com'], ['name' => 'Client 4']);
        $client5 = App\Client::firstOrCreate(['email' => 'client4@example.com'], ['name' => 'Client 5']);
    }
}
