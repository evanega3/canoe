<?php

declare(strict_types=1);

namespace App\ApiResource\DTO;

class BaseSearchRequest
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getPage(): int
    {
        return isset($this->data['page']) ? (int) $this->data['page'] : 1;
    }

    public function getOrderBy(): array
    {
        $defaultOrder = ['created_at' => 'DESC'];

        if (!isset($this->data['order_by'])) {
            return $defaultOrder;
        }

        $orderField = $this->data['order_by'];
        $orderDirection = $this->data['sort'] ?? 'ASC';

        return [$orderField => $orderDirection];
    }

    public function getCriteria(): array
    {
        $criteria = $this->data;

        unset($criteria['page'], $criteria['order_by'], $criteria['sort']);

        return $criteria;
    }
}
