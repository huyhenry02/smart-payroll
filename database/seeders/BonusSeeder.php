<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/bonuses.csv');
        $csvData = array_map('str_getcsv', file($path));
        $bonuses = [];
        foreach ($csvData as $row) {
            if (count($row) < 3) {
                continue; // Skip rows with fewer than 3 columns
            }
            $bonuses[] = [
                'name' => $row[0],
                'description' => $row[1],
                'amount' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('bonuses')->insert($bonuses);
    }
}
