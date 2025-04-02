<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Employee;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class AttendanceController extends Controller
{
    public function showDetailAttendance(Request $request): View|Factory|Application
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDetailAttendanceData($month);
        return view('page.attendance.detail', $data);
    }

    public function loadDetailTable(Request $request): Response
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDetailAttendanceData($month);
        $html = view('page.attendance.detail-table', $data)->render();
        return response($html);
    }

    public function showSummary(string $month): View|Factory|Application
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $daysInMonth = $date->daysInMonth;
        $dayWork = collect(range(1, $daysInMonth))
            ->map(function ($day) use ($date) {
                return $date->copy()->day($day);
            })
            ->reject(function ($day) {
                return in_array($day->dayOfWeekIso, [6, 7], true);
            })
            ->count();
        $attendanceData = Attendance::where('month', $date->month)
            ->where('year', $date->year)
            ->get();
        return view('page.attendance.summary',
            [
                'attendanceData' => $attendanceData,
                'month' => $month,
                'dayWork' => $dayWork
            ]);
    }

    public function showOvertime(string $month): View|Factory|Application
    {
        $date = Carbon::createFromFormat('Y-m', $month);

        $overtimes = AttendanceDetail::with(['employee.position', 'employee.department', 'workingShift'])
            ->where('is_overtime', true)
            ->whereMonth('work_date', $date->month)
            ->whereYear('work_date', $date->year)
            ->orderBy('work_date', 'desc')
            ->get();

        $workingShifts = WorkingShift::all();
        return view('page.attendance.overtime', compact('overtimes', 'month', 'workingShifts'));
    }

    public function showPersonal(string $month): View|Factory|Application
    {
        $user = auth()->user();
        $date = Carbon::createFromFormat('Y-m', $month);
        $daysInMonth = $date->daysInMonth;

        $dayWork = collect(range(1, $daysInMonth))->map(function ($day) use ($date) {
            return $date->copy()->day($day);
        })->reject(function ($d) {
            return $d->isWeekend();
        })->count();

        $attendanceData = AttendanceDetail::where('employee_id', $user->employee->id)
            ->whereMonth('work_date', $date->month)
            ->whereYear('work_date', $date->year)
            ->where('is_overtime', false)
            ->get();

        $workingDays = $attendanceData->count();
        $leaveDays = $dayWork - $workingDays;

        return view('page.attendance.personal', compact(
            'month', 'attendanceData', 'workingDays', 'leaveDays', 'dayWork'
        ));
    }

    private function getDetailAttendanceData(string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $daysInMonth = $date->daysInMonth;

        $days = collect(range(1, $daysInMonth))->map(function ($day) use ($date) {
            $current = $date->copy()->day($day);
            return [
                'date' => $current->format('Y-m-d'),
                'day' => $day,
                'weekday' => $current->dayOfWeekIso,
            ];
        });

        $employees = Employee::all();
        $attendanceData = AttendanceDetail::whereMonth('work_date', $date->month)
            ->whereYear('work_date', $date->year)
            ->where('is_overtime', 0)
            ->get()
            ->groupBy('employee_id');
        return compact('days', 'employees', 'attendanceData', 'month');
    }

    public function updateDetail(Request $request): JsonResponse
    {
        $checked = $request->input('checked', []);
        $unchecked = $request->input('unchecked', []);

        foreach ($checked as $item) {
            AttendanceDetail::firstOrCreate([
                'employee_id' => $item['employee_id'],
                'work_date' => $item['work_date'],
                'is_overtime' => false,
            ], [
                'status' => 'work',
            ]);
        }

        foreach ($unchecked as $item) {
            AttendanceDetail::where('employee_id', $item['employee_id'])
                ->where('work_date', $item['work_date'])
                ->where('is_overtime', false)
                ->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function postCloseAttendance(Request $request): RedirectResponse
    {
        $month = $request->input('month');
        $date = Carbon::createFromFormat('Y-m', $month);
        $year = $date->year;
        $monthNum = $date->month;
        try {
            DB::beginTransaction();

            $employees = Employee::all();
            $daysInMonth = $date->daysInMonth;

            foreach ($employees as $employee) {
                $workingDays = 0;
                $leaveDays = 0;
                $overtimeHours = 0;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = Carbon::createFromDate($year, $monthNum, $day);
                    $weekday = $currentDate->dayOfWeekIso;

                    if ($weekday === 6 || $weekday === 7) {
                        $overtimeHours += AttendanceDetail::where('employee_id', $employee->id)
                            ->whereDate('work_date', $currentDate)
                            ->where('is_overtime', 1)
                            ->count();
                        continue;
                    }

                    $isWorkDay = AttendanceDetail::where('employee_id', $employee->id)
                        ->whereDate('work_date', $currentDate)
                        ->where('is_overtime', 0)
                        ->exists();

                    if ($isWorkDay) {
                        $workingDays++;
                    } else {
                        $leaveDays++;
                    }

                    $overtimeHours += AttendanceDetail::where('employee_id', $employee->id)
                        ->whereDate('work_date', $currentDate)
                        ->where('is_overtime', 1)
                        ->count();
                }

                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $monthNum,
                        'year' => $year,
                    ],
                    [
                        'working_days' => $workingDays,
                        'leave_days' => $leaveDays,
                        'overtime_hours' => $overtimeHours,
                        'is_finalized' => true,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('attendance.showSummary', ['month' => $month])->with('success', 'Chốt công thành công!');
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('error', 'Chốt công thất bại: ' . $exception->getMessage());
        }
    }

    public function postOvertime(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['is_overtime'] = true;
            $attendanceDetail = new AttendanceDetail();
            $attendanceDetail->fill($input);
            $attendanceDetail->save();
            DB::commit();
            return back()->with('success', 'Tạo ca làm thêm thành công!');
        }catch (Exception $e){
            DB::rollBack();
            return back()->with('error', 'Tạo ca làm thêm thất bại!');
        }
    }

    public function putOvertime(AttendanceDetail $attendanceDetail, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $attendanceDetail->fill($input);
            $attendanceDetail->save();
            DB::commit();
            return back()->with('success', 'Cập nhật làm thêm thành công!');
        }catch (Exception $e){
            DB::rollBack();
            return back()->with('error', 'Cập nhật làm thêm thất bại!');
        }
    }
    public function deleteOvertime(AttendanceDetail $attendanceDetail): RedirectResponse
    {
        try {
            $attendanceDetail->delete();
            return back()->with('success', 'Đã xoá ca làm thêm!');
        }catch (Exception $e){
            DB::rollBack();
            return back()->with('error', 'Xoá ca làm thêm thất bại!');
        }
    }
}
