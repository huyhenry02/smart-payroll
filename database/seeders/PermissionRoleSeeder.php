<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/permission_role.csv');
        $csvData = array_map('str_getcsv', file($path));
        $permission_roles = [];
        foreach ($csvData as $row) {
            $permission_roles[] = [
                'id' => $row[0],
                'permission_id' => $row[1],
                'role_id' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('permission_role')->insert($permission_roles);
    }
}
