<?php

namespace App\Services\Ai;

use App\Exceptions\FaceAiException;
use App\Models\Employee;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FaceAiClient
{
    private function http(): PendingRequest
    {
        $req = Http::baseUrl(config('ai.face.base_url'))
            ->timeout(config('ai.face.timeout'))
            ->acceptJson()
            ->asJson();

        $apiKey = config('ai.face.api_key');
        if (!empty($apiKey)) {
            $req = $req->withHeaders([
                'X-API-KEY' => $apiKey,
            ]);
        }

        return $req;
    }

    /**
     * REGISTER MULTI
     * POST /face/register-multi
     */
    public function registerMulti(
        string $employeeId,
        string $employeeName,
        array  $imagesBase64,
        array  $metadata = []
    ): array
    {
        $payload = [
            'employee_id' => (string)$employeeId,
            'employee_name' => (string)$employeeName,
            'images_base64' => $imagesBase64,
            'metadata' => (object)$metadata,
        ];
        $res = $this->http()->post('/face/register-multi', $payload);
        if ($res->successful()) {
            return $res->json();
        }
        $status = $res->status();
        $body = $res->json() ?? [];
        $detail = $body['detail'] ?? [];

        if ($status === 409 && ($detail['error'] ?? null) === 'FACE_ALREADY_REGISTERED') {
            $similarity = round(($detail['similarity'] ?? 0) * 100, 1);
            $rawMessage = $detail['message'] ?? '';
            preg_match('/employee\s+(\d+)/', $rawMessage, $matches);
            $matchedEmployeeId = $matches[1] ?? null;
            if ($matchedEmployeeId) {
                $employee = Employee::find($matchedEmployeeId);
                $employeeName = $employee->full_name ?? null;
            }
            $vnMessage = $employeeName
                ? "Khuôn mặt này đã được đăng ký cho nhân viên {$employeeName} (độ trùng khớp {$similarity}%)."
                : "Khuôn mặt này đã được đăng ký cho một nhân viên khác (độ trùng khớp {$similarity}%).";
            throw new FaceAiException(
                $vnMessage,
                409,
                [
                    'error_code' => 'FACE_ALREADY_REGISTERED',
                    'matched_employee_id' => $matchedEmployeeId,
                    'matched_employee_name' => $employeeName,
                    'similarity' => $similarity,
                ]
            );
        }
        throw new FaceAiException(
            'Không thể đăng ký khuôn mặt. Vui lòng thử lại hoặc liên hệ quản trị.',
            $status,
            $detail
        );
    }

    /**
     * DETECT/RECOGNIZE
     * POST /face/recognize
     */
    public function recognize(string $imageBase64, ?float $threshold = null, ?int $topK = null): array
    {
        $payload = [
            'image_base64' => $imageBase64,
            'threshold' => $threshold ?? config('ai.face.threshold'),
            'top_k' => $topK ?? config('ai.face.top_k'),
        ];

        $res = $this->http()->post('/face/recognize', $payload);

        if (!$res->successful()) {
            throw new RuntimeException('AI recognize failed: ' . $res->status() . ' ' . $res->body());
        }

        return $res->json();
    }
}
