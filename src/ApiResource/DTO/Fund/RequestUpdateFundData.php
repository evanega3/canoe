<?php

declare(strict_types=1);

namespace App\ApiResource\DTO\Fund;

use App\Entity\Companies;

final class RequestUpdateFundData
{
    private array $data;
    private Companies $manager;

    /**
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getManager(): Companies
    {
        return $this->manager;
    }

    public function setManager(Companies $manager): void
    {
        $this->manager = $manager;
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * @throws \Exception
     */
    public function getStartYear(): string
    {
        return $this->data['start_year'];
    }

    public function getFundManager(): int
    {
        return $this->data['manager'];
    }

    public function getAliases(): array
    {
        return $this->data['aliases'];
    }
}