<?php

namespace App\Services\Ai;

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
    public function registerMulti(string $employeeId, string $employeeName, array $imagesBase64, array $metadata = []): array
    {
        $payload = [
            'employee_id'   => (string) $employeeId,
            'employee_name' => (string) $employeeName,
            'images_base64' => $imagesBase64,
            'metadata'      => (object) $metadata,
        ];

        $res = $this->http()->post('/face/register-multi', $payload);

        if (!$res->successful()) {
            throw new RuntimeException('AI register-multi failed: ' . $res->status() . ' ' . $res->body());
        }

        return $res->json();
    }

    /**
     * DETECT/RECOGNIZE
     * POST /face/recognize
     */
    public function recognize(string $imageBase64, ?float $threshold = null, ?int $topK = null): array
    {
        $payload = [
            'image_base64' => $imageBase64,
            'threshold'    => $threshold ?? config('ai.face.threshold'),
            'top_k'        => $topK ?? config('ai.face.top_k'),
        ];

        $res = $this->http()->post('/face/recognize', $payload);

        if (!$res->successful()) {
            if ($res->status() === 409) {
                throw new RuntimeException('Khuôn mặt đã tồn tại trong hệ thống');
            }
            throw new RuntimeException('AI recognize failed: ' . $res->status() . ' ' . $res->body());
        }

        return $res->json();
    }
}
