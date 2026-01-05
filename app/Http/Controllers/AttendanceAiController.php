<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
use App\Models\Employee;
use App\Services\Ai\FaceRecognitionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceAiController extends Controller
{
    private function nowVn(): Carbon
    {
        return Carbon::now('Asia/Ho_Chi_Minh');
    }

    private function isWeekend(Carbon $date): bool
    {
        return $date->isSaturday() || $date->isSunday();
    }

    public function checkIn(Request $request, FaceRecognitionService $faceService): JsonResponse
    {
        $request->validate(['snapshot' => ['required', 'string']]);
        $ai = $faceService->recognizeFromDataUrl($request->snapshot);
        if (!($ai['matched'] ?? false)) {
            return response()->json(['message' => 'Không nhận diện được', 'ai' => $ai], 422);
        }
        $employeeId = (int)($ai['employee_id'] ?? 0);
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return response()->json(['message' => 'Không tìm thấy nhân viên'], 422);
        }
        $now = $this->nowVn();
        $workDate = $now->toDateString();
        $standardCheckIn = Carbon::createFromTime(8, 0, 0, 'Asia/Ho_Chi_Minh');
        $isLate = $now->gt($standardCheckIn);
        $isOvertime = $this->isWeekend($now);
        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();
        if ($detail && $detail->check_in) {
            return response()->json(['message' => 'Hôm nay đã check-in'], 200);
        }
        $detail = AttendanceDetail::updateOrCreate(
            ['employee_id' => $employeeId, 'work_date' => $workDate],
            [
                'check_in'     => $now,
                'check_out'    => $now,
                'is_late'      => $isLate,
                'is_early'     => false,
                'is_full_day'  => false,
                'is_overtime'  => $isOvertime,
            ]
        );
        return response()->json([
            'message' => 'Check-in thành công',
            'time' => [
                'work_date' => $workDate,
                'check_in' => $detail->check_in->toDateTimeString(),
            ],
            'is_late' => $detail->is_late,
            'is_overtime' => $detail->is_overtime,
            'ai' => $ai
        ]);
    }

    public function checkOut(Request $request, FaceRecognitionService $faceService): JsonResponse
    {
        $request->validate(['snapshot' => ['required', 'string']]);

        $ai = $faceService->recognizeFromDataUrl($request->snapshot);
        if (!($ai['matched'] ?? false)) {
            return response()->json(['message' => 'Không nhận diện được', 'ai' => $ai], 422);
        }

        $employeeId = (int)($ai['employee_id'] ?? 0);
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return response()->json(['message' => 'Không tìm thấy nhân viên'], 422);
        }

        $now = $this->nowVn();
        $workDate = $now->toDateString();

        $detail = AttendanceDetail::where('employee_id', $employeeId)
            ->whereDate('work_date', $workDate)
            ->first();

        if (!$detail) {
            return response()->json(['message' => 'Chưa check-in'], 422);
        }

        $standardCheckOut = Carbon::createFromTime(17, 30, 0, 'Asia/Ho_Chi_Minh');
        $isEarly = $now->lt($standardCheckOut);

        $detail->check_out = $now;
        $detail->is_early = $isEarly;

        $detail->is_full_day = !$detail->is_late && !$detail->is_early;

        if ($this->isWeekend($now)) {
            $detail->is_overtime = true;
        }

        $detail->save();

        return response()->json([
            'message' => 'Check-out thành công',
            'time' => [
                'work_date' => $workDate,
                'check_in' => Carbon::parse($detail->check_in, 'Asia/Ho_Chi_Minh')->toDateTimeString(),
                'check_out' => Carbon::parse($detail->check_out, 'Asia/Ho_Chi_Minh')->toDateTimeString(),
            ],
            'flags' => [
                'is_full_day' => (bool) $detail->is_full_day,
                'is_late' => (bool) $detail->is_late,
                'is_early' => (bool) $detail->is_early,
                'is_overtime' => (bool) $detail->is_overtime,
            ],
            'ai' => $ai
        ]);
    }

}
