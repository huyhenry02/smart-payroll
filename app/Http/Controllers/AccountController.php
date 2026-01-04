<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function showInformation()
    {
        $employee = auth()->user()->employee;
        return view('page.account.information.index',
        [
            'employee' => $employee,
        ]);
    }

    public function showPersonalAttendance(string $month)
    {
        $user = auth()->user();
        $employeeId = $user->employee->id;

        $date = Carbon::createFromFormat('Y-m', $month);
        $daysInMonth = $date->daysInMonth;

        $dayWork = collect(range(1, $daysInMonth))->map(function ($day) use ($date) {
            return $date->copy()->day($day);
        })->reject(function ($d) {
            return $d->isWeekend();
        })->count();

        $attendanceData = AttendanceDetail::where('employee_id', $employeeId)
            ->whereMonth('work_date', $date->month)
            ->whereYear('work_date', $date->year)
            ->where('is_overtime', false)
            ->get();

        $workingDays = $attendanceData->count();
        $leaveDays = $dayWork - $workingDays;

        $today = Carbon::today()->toDateString();

        $todayDetail = AttendanceDetail::query()
            ->where('employee_id', $employeeId)
            ->whereDate('work_date', $today)
            ->where('is_overtime', false)
            ->first();

        $todayState = 'need_checkin';

        if ($todayDetail) {
            $checkIn = Carbon::parse($todayDetail->check_in);
            $checkOut = Carbon::parse($todayDetail->check_out);

            $todayState = $checkOut->diffInMinutes($checkIn) >= 1
                ? 'done'
                : 'need_checkout';
        }

        return view('page.account.attendance.personal', compact(
            'month',
            'attendanceData',
            'workingDays',
            'leaveDays',
            'dayWork',
            'todayState',
            'todayDetail'
        ));
    }

    public function showPersonalAccounting()
    {
        return view('page.account.accounting.personal');
    }
}
