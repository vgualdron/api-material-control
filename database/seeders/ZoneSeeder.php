<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Zone::create(["name" => "BOYACÁ" ]);
        Zone::create(["name" => "CUNDINAMARCA" ]);        
        Zone::create(["name" => "NORTE DE SANTANDER" ]);               
    }
}
