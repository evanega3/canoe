<?php

declare(strict_types=1);

namespace App\ApiResource\UseCases\Company;

use App\ApiResource\DTO\Company\RequestSearchCompany;
use App\Repository\Companies\CompaniesRepository;

class SearchCompanyUseCase
{
    private CompaniesRepository $companiesRepository;

    public function __construct(CompaniesRepository $companiesRepository)
    {
        $this->companiesRepository = $companiesRepository;
    }

    public function execute(RequestSearchCompany $requestSearchCompany): array
    {
        $criteria = $requestSearchCompany->getCriteria();
        $orderBy = $requestSearchCompany->getOrderBy();
        $page = $requestSearchCompany->getPage();

        $result = $this->companiesRepository->findByCriteria($criteria, $orderBy, $page);
        $result['resultSet'] = array_map(fn ($company) => $company->toArrayResponse(), $result['resultSet']);

        return $result;
    }
}