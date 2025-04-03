<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/employee_allowances.csv');
        $csvData = array_map('str_getcsv', file($path));
        $employee_allowances = [];
        foreach ($csvData as $row) {
            $employee_allowances[] = [
                'employee_id' => $row[0],
                'allowance_id' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('employee_allowances')->insert($employee_allowances);
    }
}
