<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        foreach($clients as $client){

            Compte::factory()->create(['titulaire'=>$client->id]);

        }
    }
}
