<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/permissions.csv');
        $csvData = array_map('str_getcsv', file($path));
        $bonuses = [];
        foreach ($csvData as $row) {
            if (count($row) < 3) {
                continue;
            }
            $bonuses[] = [
                'module_code' => $row[0],
                'code' => $row[1],
                'name' => $row[2],
                'description' => $row[3],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('permissions')->insert($bonuses);
    }
}
