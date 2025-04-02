<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/attendance_details.csv');
        $csvData = array_map('str_getcsv', file($path));
        $attendance_details = [];
        foreach ($csvData as $row) {
            $attendance_details[] = [
                'employee_id' => $row[0],
                'work_date' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('attendance_details')->insert($attendance_details);
    }
}
