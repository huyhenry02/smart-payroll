<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
use App\Models\Employee;
use App\Services\Ai\FaceRecognitionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceAiController extends Controller
{

    public function checkIn(Request $request, FaceRecognitionService $faceService)
    {
        $request->validate([
            'snapshot' => ['required', 'string'],
        ]);

        $ai = $faceService->recognizeFromDataUrl($request->snapshot);

        if (!($ai['matched'] ?? false)) {
            return response()->json(['message' => 'Không nhận diện được', 'ai' => $ai], 422);
        }

        $employeeId = (int) ($ai['employee_id'] ?? 0);
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([
                'message' => 'Không tìm thấy nhân viên tương ứng trong hệ thống',
                'employee_id' => $employeeId,
                'ai' => $ai
            ], 422);
        }

        $now = now();
        $workDate = $now->toDateString();

        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();

        if ($detail && $detail->check_in) {
            return response()->json([
                'message' => 'Hôm nay đã check-in',
                'attendance_detail_id' => $detail->id,
                'employee' => [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'full_name' => $employee->full_name,
                ],
                'time' => [
                    'work_date' => $workDate,
                    'check_in' => optional($detail->check_in)->toDateTimeString(),
                    'check_out' => optional($detail->check_out)->toDateTimeString(),
                ],
                'ai' => $ai
            ], 200);
        }

        $detail = AttendanceDetail::updateOrCreate(
            ['employee_id' => $employeeId, 'work_date' => $workDate],
            [
                'check_in' => $now,
                'check_out' => $now,
                'is_full_day' => false,
                'is_overtime' => false,
                'working_shift_id' => null,
                'attendance_id' => null,
            ]
        );

        return response()->json([
            'message' => 'Check-in thành công',
            'attendance_detail_id' => $detail->id,
            'employee' => [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
            ],
            'time' => [
                'work_date' => $workDate,
                'check_in' => $detail->check_in->toDateTimeString(),
                'check_out' => $detail->check_out->toDateTimeString(),
            ],
            'ai' => $ai
        ]);
    }

    public function checkOut(Request $request, FaceRecognitionService $faceService)
    {
        $request->validate([
            'snapshot' => ['required', 'string'],
        ]);

        $ai = $faceService->recognizeFromDataUrl($request->snapshot);

        if (!($ai['matched'] ?? false)) {
            return response()->json(['message' => 'Không nhận diện được', 'ai' => $ai], 422);
        }

        $employeeId = (int) ($ai['employee_id'] ?? 0);
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([
                'message' => 'Không tìm thấy nhân viên tương ứng trong hệ thống',
                'employee_id' => $employeeId,
                'ai' => $ai
            ], 422);
        }

        $now = now();
        $workDate = $now->toDateString();

        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();

        if (!$detail) {
            return response()->json([
                'message' => 'Chưa có check-in hôm nay',
                'employee' => [
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'full_name' => $employee->full_name,
                ],
                'ai' => $ai
            ], 422);
        }

        $detail->check_out = $now;

        $hours = Carbon::parse($detail->check_in)->diffInMinutes($detail->check_out) / 60;
        $detail->is_full_day = $hours >= 8;

        $detail->save();

        return response()->json([
            'message' => 'Check-out thành công',
            'attendance_detail_id' => $detail->id,
            'employee' => [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
            ],
            'time' => [
                'work_date' => $workDate,
                'check_in' => Carbon::parse($detail->check_in)->toDateTimeString(),
                'check_out' => Carbon::parse($detail->check_out)->toDateTimeString(),
            ],
            'worked_hours' => round($hours, 2),
            'ai' => $ai
        ]);
    }

}
