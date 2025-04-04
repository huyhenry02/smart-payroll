<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/allowances.csv');
        $csvData = array_map('str_getcsv', file($path));
        $allowances = [];
        foreach ($csvData as $row) {
            $allowances[] = [
                'name' => $row[0],
                'type' => $row[1],
                'rate' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('allowances')->insert($allowances);
    }
}
