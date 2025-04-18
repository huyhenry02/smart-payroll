<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DeductionSeeder::class,
            AllowanceSeeder::class,
            WorkingShift::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            EmployeeDeductionSeeder::class,
            EmployeeAllowanceSeeder::class,
            AttendanceDetailSeeder::class,
            BonusSeeder::class,
        ]);
    }
}
