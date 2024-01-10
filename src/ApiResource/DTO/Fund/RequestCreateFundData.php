<?php

declare(strict_types=1);

namespace App\ApiResource\DTO\Fund;

use Symfony\Component\HttpFoundation\Response;

final class RequestCreateFundData
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
    public function getStartYear(): string
    {
        return $this->data['start_year'];
    }

    public function getManager(): int
    {
        return $this->data['manager'];
    }

    public function getAliases(): array
    {
        return $this->data['aliases'];
    }

    /**
     * @throws \Exception
     */
    private function validate(array $data): void
    {
        if (empty($data['name'])) {
            throw new \Exception('Field name is required', Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['start_year'])) {
            throw new \Exception('Field start_year is required', Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['manager'])) {
            throw new \Exception('Field manager is required', Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['aliases'])) {
            throw new \Exception('Field alias is required', Response::HTTP_BAD_REQUEST);
        }
    }
}
