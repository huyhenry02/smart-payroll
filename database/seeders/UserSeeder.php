<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/users.csv');
        $csvData = array_map('str_getcsv', file($path));
        $users = [];
        foreach ($csvData as $row) {
            $users[] = [
                'id' => $row[0],
                'email' => $row[1],
                'role' => $row[2],
                'password' => bcrypt(1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);
    }
}
