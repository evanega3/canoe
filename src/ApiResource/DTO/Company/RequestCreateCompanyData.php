<?php

declare(strict_types=1);

namespace App\ApiResource\DTO\Company;
use Symfony\Component\HttpFoundation\Response;

final class RequestCreateCompanyData
{
    private array $data;

    /**
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->validate($data);
        $this->data = $data;
    }

    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }

    /**
     * @throws \Exception
     */
    private function validate(array $data): void
    {
        if (empty($data['name'])) {
            throw new \Exception('Field name is required', Response::HTTP_BAD_REQUEST);
        }
    }
}