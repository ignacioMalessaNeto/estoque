<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array();

        for ($i = 0; $i < 4; $i++) {
            $data[] =
                [
                    "name" => "user{$i}",
                    "email" => "user{$i}@gmail.com",
                    "password" => bcrypt("123321"),
                    "created_at" => date("Y-m-d H:i:s"),
                    "level_access" => $i
                ];
        }

        DB::table("users")->insert($data);
    }
}
