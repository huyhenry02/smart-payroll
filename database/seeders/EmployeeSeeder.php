<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $path = database_path('seeders/data/employees.csv');
        $csvData = array_map('str_getcsv', file($path));
        $employees = [];
        foreach ($csvData as $row) {
            $employees[] = [
                'id' => $row[0],
                'user_id' => $row[1],
                'employee_code' => $row[2],
                'full_name' => $row[3],
                'dob' => $row[4],
                'gender' => $row[5],
                'identity_number' => $row[6],
                'identity_issued_date' => $row[7],
                'identity_issued_place' => $row[8],
                'address' => $row[9],
                'phone' => $row[10],
                'department_id' => $row[11],
                'position_id' => $row[12],
                'start_date' => $row[13],
                'employment_status' => $row[14],
                'contract_type' => $row[15],
                'salary_factor' => $row[17],
                'seniority' => $row[18],
                'tax_code' => $row[19],
                'bank_account' => $row[20],
                'bank_name' => $row[21],
                'education_level' => $row[22],
                'specialization' => $row[23],
                'number_of_dependent' => random_int(1,2),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('employees')->insert($employees);
    }
}
