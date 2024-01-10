<?php

declare(strict_types=1);

namespace App\ApiResource\UseCases\Fund;

use App\ApiResource\DTO\Fund\RequestSearchFund;
use App\Repository\Funds\FundsRepository;

class SearchFundsUseCase
{
    private FundsRepository $fundsRepository;

    public function __construct(FundsRepository $fundsRepository)
    {
        $this->fundsRepository = $fundsRepository;
    }

    public function execute(RequestSearchFund $requestSearchFund): array
    {
        $criteria = $requestSearchFund->getCriteria();
        $orderBy = $requestSearchFund->getOrderBy();
        $page = $requestSearchFund->getPage();

        $result = $this->fundsRepository->findByCriteria($criteria, $orderBy, $page);
        $result['resultSet'] = array_map(fn ($fund) => $fund->toArrayResponse(), $result['resultSet']);

        return $result;
    }
}