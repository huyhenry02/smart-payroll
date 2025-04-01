<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

}
