<?php

declare(strict_types=1);

namespace App\Controller\v1\Company;

use App\ApiResource\DTO\Company\RequestSearchCompany;
use App\ApiResource\Responses\APIResponse;
use App\ApiResource\UseCases\Company\SearchCompanyUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchCompanyController extends AbstractController
{
    private SearchCompanyUseCase $searchCompanyUseCase;

    public function __construct(SearchCompanyUseCase $searchCompanyUseCase)
    {
        $this->searchCompanyUseCase = $searchCompanyUseCase;
    }

    #[Route('api/v1/search/company', name: 'app_search_company', methods: ['GET'])]
    public function __invoke(Request $request): APIResponse
    {
        try {
            $requestSearchCompany = new RequestSearchCompany($request->query->all());

            $funds = $this->searchCompanyUseCase->execute($requestSearchCompany);

            return new APIResponse('', $funds, [], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new APIResponse(
                $exception->getMessage(),
                null,
                [],
                $exception->getCode() ?: Response::HTTP_BAD_REQUEST
            );
        }
    }
}
