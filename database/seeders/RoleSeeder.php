<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/roles.csv');
        $csvData = array_map('str_getcsv', file($path));
        $roles = [];
        foreach ($csvData as $row) {
            $roles[] = [
                'id' => $row[0],
                'code' => $row[1],
                'name' => $row[2],
                'description' => $row[3],
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('roles')->insert($roles);
    }
}
