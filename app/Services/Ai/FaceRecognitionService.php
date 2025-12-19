<?php

namespace App\Services\Ai;

use InvalidArgumentException;

readonly class FaceRecognitionService
{
    public function __construct(
        private FaceAiClient $client
    )
    {
    }

    /**
     * Nhận mảng dataURL (data:image/jpeg;base64,xxx) hoặc base64 thuần
     * => chuẩn hoá thành base64 thuần để gửi AI
     */
    public function normalizeBase64(string $input): string
    {
        $input = trim($input);

        if (str_starts_with($input, 'data:image')) {
            $parts = explode(',', $input, 2);
            if (count($parts) !== 2) {
                throw new InvalidArgumentException('Invalid data URL format.');
            }
            return $parts[1];
        }

        return $input;
    }

    public function enrollEmployeeFace(string $employeeId, string $employeeName, array $dataUrls, int $required = 10): array
    {
        if (count($dataUrls) < $required) {
            throw new InvalidArgumentException("Cần tối thiểu {$required} ảnh để đăng ký khuôn mặt.");
        }

        $imagesBase64 = array_map(fn($x) => $this->normalizeBase64((string)$x), $dataUrls);

        return $this->client->registerMulti(
            employeeId: $employeeId,
            employeeName: $employeeName,
            imagesBase64: $imagesBase64,
            metadata: [
                'source' => 'laravel-web',
                'type' => 'enroll',
            ]
        );
    }

    public function recognizeFromDataUrl(string $dataUrl, ?float $threshold = null, ?int $topK = null): array
    {
        $base64 = $this->normalizeBase64($dataUrl);
        return $this->client->recognize($base64, $threshold, $topK);
    }
}
