<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeDeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/employee_deductions.csv');
        $csvData = array_map('str_getcsv', file($path));
        $employee_deductions = [];
        foreach ($csvData as $row) {
            $employee_deductions[] = [
                'employee_id' => $row[0],
                'deduction_id' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('employee_deductions')->insert($employee_deductions);
    }
}
