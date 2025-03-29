<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/deductions.csv');
        $csvData = array_map('str_getcsv', file($path));
        $deductions = [];
        foreach ($csvData as $row) {
            $deductions[] = [
                'name' => $row[0],
                'rate' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('deductions')->insert($deductions);
    }
}
