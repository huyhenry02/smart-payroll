<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/positions.csv');
        $csvData = array_map('str_getcsv', file($path));
        $positions = [];
        foreach ($csvData as $row) {
            $positions[] = [
                'id' => $row[0],
                'name' => $row[1],
                'description' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('positions')->insert($positions);
    }
}
