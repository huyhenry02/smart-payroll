<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
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

        $employeeId = (int) $ai['employee_id'];
        $now = now();
        $workDate = $now->toDateString();

        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();

        if ($detail && $detail->checkIn) {
            return response()->json([
                'message' => 'Hôm nay đã check-in',
                'attendance_detail_id' => $detail->id,
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
            'employee_id' => $employeeId,
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

        $employeeId = (int) $ai['employee_id'];
        $now = now();
        $workDate = $now->toDateString();

        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();

        if (!$detail) {
            return response()->json([
                'message' => 'Chưa có check-in hôm nay',
                'ai' => $ai
            ], 422);
        }

        $detail->checkOut = $now;

        $hours = Carbon::parse($detail->checkIn)->diffInMinutes($detail->checkOut) / 60;
        $detail->isFullDay = $hours >= 8;

        $detail->save();

        return response()->json([
            'message' => 'Check-out thành công',
            'attendance_detail_id' => $detail->id,
            'employee_id' => $employeeId,
            'worked_hours' => round($hours, 2),
            'ai' => $ai
        ]);
    }
}
