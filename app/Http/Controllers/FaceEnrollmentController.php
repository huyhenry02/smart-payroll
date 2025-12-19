<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\Ai\FaceRecognitionService;
use Illuminate\Http\Request;

class FaceEnrollmentController extends Controller
{
    public function show(Employee $employee)
    {
        return view('page.general_catalog.employee.set-up-adaface',
            [
                'employee' => $employee,
            ]);
    }

    public function store(Request $request, Employee $employee, FaceRecognitionService $faceService)
    {
        $request->validate([
            'face_photos' => ['required', 'array', 'min:5'],
            'face_photos.*' => ['required', 'string'],
        ]);

        $photos = $request->input('face_photos');

        $aiResult = $faceService->enrollEmployeeFace(
            employeeId: (string)$employee->id,
            employeeName: (string)$employee->full_name,
            dataUrls: $photos,
        );

         $employee->face_enroll_status = 'active';
         $employee->face_enrolled_at = now();
         $employee->save();

        return response()->json([
            'message' => 'Đăng ký khuôn mặt thành công',
            'ai' => $aiResult,
        ]);
    }
}
