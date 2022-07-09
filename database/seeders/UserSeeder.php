<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin",
            "document_number" => "Admin",
            "phone" => "0000000",
            "password" => Hash::make('admin')]
        )->assignRole('Admin');

        /*User::create([
                    "first_name" => "Luisa",
                    "last_name" => "Lopez",
                    "document_number" => "1045454545",
                    "phone" => "5762424",
                    "email" => "luisal@hotmail.com",
                    "password" => Hash::make('luisa123')]
        );
        
        User::create([
                    "first_name" => "Manuel",
                    "last_name" => "Garcia",
                    "document_number" => "10926544",
                    "phone" => "571654",
                    "email" => "guillo.01@hotmail.com",
                    "password" => Hash::make('123456')]
        );*/
        
    }
}
