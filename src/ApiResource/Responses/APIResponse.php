<?php

declare(strict_types=1);

namespace App\ApiResource\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

final class APIResponse extends JsonResponse
{
    public function __construct(string $message = '', $data = null, array $errors = [], int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct([
            'status' => $status,
            'status_text' => self::$statusTexts[$status],
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $status, $headers, $json);
    }
}
