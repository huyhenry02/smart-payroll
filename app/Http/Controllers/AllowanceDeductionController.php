<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class AllowanceDeductionController extends Controller
{
    public function showIndexDeduction(): View|Factory|Application
    {
        $deductions = Deduction::all()->keyBy('id');
        $employees = Employee::all()->keyBy('id');
        $employee_deductions = EmployeeDeduction::all();
        $deduction_names = $deductions->pluck('name')->all();
        $data = [];

        foreach ($employee_deductions as $ed) {
            $employee = $employees[$ed->employee_id] ?? null;
            $deductionName = $deductions[$ed->deduction_id]->name ?? null;
            if (!$employee || !$deductionName) {
                continue;
            }

            if (!isset($data[$employee->id])) {
                $data[$employee->id] = [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'position' => $employee->position->name,
                    'full_name' => $employee->full_name,
                    'deductions' => array_fill_keys($deduction_names, false),
                ];
            }

            $data[$employee->id]['deductions'][$deductionName] = true;
        }
        return view('page.allowance_deduction.deduction.index', [
            'data' => $data,
            'deductions' => $deductions
        ]);
    }

    public function showIndexAllowance(): View|Factory|Application
    {
        return view('page.allowance_deduction.allowance.index');
    }
}
