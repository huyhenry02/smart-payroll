<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Bonus;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeBonus;
use App\Models\Payroll;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function showIndex(Request $request): View|Factory|Application
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDataAccountingTable($month);
        return view('page.accounting.index', $data);
    }
    public function loadIndex(Request $request): Response
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDataAccountingTable($month);

        $html = view('page.accounting.index-table', $data)->render();
        return response($html);
    }

    private function getDataAccountingTable(string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $monthInt = $date->month;
        $year = $date->year;

        $allowanceTypes = ['position', 'region', 'responsibility'];
        $allowances = Allowance::whereIn('type', $allowanceTypes)->get();
        $deductions = Deduction::all();
        $daysInMonth = $date->daysInMonth;
        $workingDaysRequired = collect(range(1, $daysInMonth))->filter(function ($day) use ($date) {
            $d = $date->copy()->day($day);
            return !in_array($d->dayOfWeek, [CarbonInterface::SATURDAY, CarbonInterface::SUNDAY], true);
        })->count();
        $employees = Employee::with([
            'position',
            'allowances',
            'deductions',
            'payrolls' => function ($q) use ($monthInt, $year) {
                $q->where('month', $monthInt)->where('year', $year);
            },
            'attendance' => function ($q) use ($monthInt, $year) {
                $q->where('month', $monthInt)->where('year', $year);
            },
        ])->get();

        return [
            'employees' => $employees,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'workingDaysRequired' => $workingDaysRequired,
            'month' => $month,
        ];
    }

    public function showEmployeeBonus(Request $request): View|Factory|Application
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getEmployeeBonusData($month);

        return view('page.accounting.employee_bonus', $data);
    }

    public function loadEmployeeBonusTable(Request $request): Response
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getEmployeeBonusData($month);

        $html = view('page.accounting.employee_bonus_table', $data)->render();
        return response($html);
    }

    private function getEmployeeBonusData(string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $employees = Employee::with('position')->get();
        $bonuses = Bonus::all()->keyBy('id');
        $employeeBonuses = EmployeeBonus::whereYear('month', $date->year)
            ->whereMonth('month', $date->month)
            ->get()
            ->groupBy('employee_id');
        return compact('employees', 'bonuses', 'employeeBonuses', 'month');
    }

    public function updateEmployeeBonus(Request $request): JsonResponse
    {
        $month = Carbon::createFromFormat('Y-m', $request->input('month'));
        $checked = $request->input('checked', []);
        $unchecked = $request->input('unchecked', []);
        $monthDate = $month->day(15)->format('Y-m-d');
        foreach ($checked as $item) {
            EmployeeBonus::updateOrInsert([
                'employee_id' => $item['employee_id'],
                'bonus_id' => $item['bonus_id'],
                'month' => $monthDate,
            ]);
        }

        foreach ($unchecked as $item) {
            EmployeeBonus::where('employee_id', $item['employee_id'])
                ->where('bonus_id', $item['bonus_id'])
                ->whereDate('month', $monthDate)
                ->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function postPayrollTable(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $monthDate = Carbon::createFromFormat('Y-m', $request->input('month'));
            $month = $monthDate->month;
            $year = $monthDate->year;
            $employees = Employee::with(['allowances', 'deductions'])->get();
            foreach ($employees as $employee) {
                $period = CarbonPeriod::create("{$year}-{$month}-01", "{$year}-{$month}-" . $monthDate->daysInMonth);
                $workingDaysRequired = collect($period)->filter(function ($item) {
                   return !in_array($item->dayOfWeek, [CarbonInterface::SATURDAY, CarbonInterface::SUNDAY], true);
                })->count();

                $attendance = Attendance::where('employee_id', $employee->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                $actualWorkingDays = $attendance ? $attendance->working_days : 0;

                $salaryV1 = (int)($employee->salary_factor * Payroll::BASE_SALARY / $workingDaysRequired * $actualWorkingDays);

                $totalAllowance = $employee->allowances->sum(function ($allowance) {
                    return $allowance->allowance->rate * Payroll::BASE_SALARY;
                });

                $totalDeduction = $employee->deductions->sum(function ($deduction) use ($salaryV1) {
                    return $deduction->deduction->rate * $salaryV1;
                });

                $overtimeDetails = AttendanceDetail::where('employee_id', $employee->id)
                    ->whereYear('work_date', $year)
                    ->whereMonth('work_date', $month)
                    ->where('is_overtime', true)
                    ->with('workingShift')
                    ->get();
                $workingShiftAmount = $overtimeDetails->sum(fn($detail) => $detail->workingShift?->hourly_rate ?? 0);

                $bonusIds = EmployeeBonus::where('employee_id', $employee->id)
                    ->whereMonth('month', $month)
                    ->whereYear('month', $year)
                    ->pluck('bonus_id');
                $totalBonus = Bonus::whereIn('id', $bonusIds)->sum('amount');

                $netBeforeTax = $salaryV1 + $totalAllowance + $workingShiftAmount + $totalBonus - $totalDeduction;
                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'salary_v1' => $salaryV1,
                        'total_allowance' => $totalAllowance,
                        'total_deduction' => $totalDeduction,
                        'working_shift_amount' => $workingShiftAmount,
                        'total_bonus' => $totalBonus,
                        'net_salary_before_tax' => $netBeforeTax,
                    ]
                );
            }
            DB::commit();
            return redirect()->route('accounting.showIndex')->with('success', 'Tạo bảng lương thành công');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->route('accounting.showIndex')->with('error', 'Tạo bảng lương thất bại');
        }
    }
}
