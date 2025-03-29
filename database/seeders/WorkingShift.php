<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingShift extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/working_shifts.csv');
        $csvData = array_map('str_getcsv', file($path));
        $working_shifts = [];
        foreach ($csvData as $row) {
            $working_shifts[] = [
                'type' => $row[0],
                'hourly_rate' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('working_shifts')->insert($working_shifts);
    }
}
