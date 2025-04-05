<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeAllowance;
use App\Models\EmployeeDeduction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function putDeduction(Request $request): JsonResponse
    {
        $input = $request->all();
        $existing = EmployeeDeduction::all()->groupBy('employee_id');
        foreach ($input['deductions'] ?? [] as $employee_id => $deduction_ids) {
            $existingDeductions = $existing[$employee_id] ?? collect();

            foreach ($existingDeductions as $ed) {
                if (!array_key_exists($ed->deduction_id, $deduction_ids)) {
                    EmployeeDeduction::where('employee_id', $employee_id)
                        ->where('deduction_id', $ed->deduction_id)->delete();
                }
            }

            foreach (array_keys($deduction_ids) as $deduction_id) {
                EmployeeDeduction::firstOrCreate([
                    'employee_id' => $employee_id,
                    'deduction_id' => $deduction_id,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function previewDeductionPdf(): Response
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
                    'position' => $employee->position->name ?? '',
                    'full_name' => $employee->full_name,
                    'deductions' => array_fill_keys($deduction_names, false),
                ];
            }

            $data[$employee->id]['deductions'][$deductionName] = true;
        }
        return PDF::loadView('page.template-download-file.summary-deduction', [
            'data' => $data,
            'deductions' => $deductions,
        ])->setPaper('A4', 'landscape')->stream("TONG_HOP_CAC_KHOAN_TRICH_HOP.pdf");
    }

    public function showIndexAllowance(): View|Factory|Application
    {
        $allowances = Allowance::all()->keyBy('id');
        $employees = Employee::all()->keyBy('id');
        $employee_allowances = EmployeeAllowance::all();
        $allowance_names = $allowances->pluck('name')->all();
        $data = [];

        foreach ($employee_allowances as $ea) {
            $employee = $employees[$ea->employee_id] ?? null;
            $allowanceName = $allowances[$ea->allowance_id]->name ?? null;
            if (!$employee || !$allowanceName) {
                continue;
            }

            if (!isset($data[$employee->id])) {
                $data[$employee->id] = [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'position' => $employee->position->name,
                    'full_name' => $employee->full_name,
                    'allowances' => array_fill_keys($allowance_names, false),
                ];
            }

            $data[$employee->id]['allowances'][$allowanceName] = true;
        }
        return view('page.allowance_deduction.allowance.index', [
            'data' => $data,
            'allowances' => $allowances
        ]);
    }

    public function putAllowance(Request $request): JsonResponse
    {
        $input = $request->all();
        $existing = EmployeeAllowance::all()->groupBy('employee_id');

        foreach ($input['allowances'] ?? [] as $employee_id => $allowance_ids) {
            $existingAllowances = $existing[$employee_id] ?? collect();

            foreach ($existingAllowances as $ed) {
                if (!array_key_exists($ed->allowance_id, $allowance_ids)) {
                    EmployeeAllowance::where('employee_id', $employee_id)
                        ->where('allowance_id', $ed->allowance_id)->delete();
                }
            }

            foreach (array_keys($allowance_ids) as $allowance_id) {
                EmployeeAllowance::firstOrCreate([
                    'employee_id' => $employee_id,
                    'allowance_id' => $allowance_id,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function previewAllowancePdf(): Response
    {
        $allowances = Allowance::all()->keyBy('id');
        $employees = Employee::all()->keyBy('id');
        $employee_allowances = EmployeeAllowance::all();
        $allowance_names = $allowances->pluck('name')->all();

        $data = [];

        foreach ($employee_allowances as $ed) {
            $employee = $employees[$ed->employee_id] ?? null;
            $allowanceName = $allowances[$ed->allowance_id]->name ?? null;

            if (!$employee || !$allowanceName) {
                continue;
            }

            if (!isset($data[$employee->id])) {
                $data[$employee->id] = [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'position' => $employee->position->name ?? '',
                    'full_name' => $employee->full_name,
                    'allowances' => array_fill_keys($allowance_names, false),
                ];
            }

            $data[$employee->id]['allowances'][$allowanceName] = true;
        }
        return PDF::loadView('page.template-download-file.summary-allowance', [
            'data' => $data,
            'allowances' => $allowances,
        ])->setPaper('A4', 'landscape')->stream("TONG_HOP_CAC_KHOAN_TRICH_HOP.pdf");
    }
}
