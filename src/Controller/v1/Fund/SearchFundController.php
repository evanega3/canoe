<?php

declare(strict_types=1);

namespace App\Controller\v1\Fund;

use App\ApiResource\DTO\Fund\RequestSearchFund;
use App\ApiResource\Responses\APIResponse;
use App\ApiResource\UseCases\Fund\SearchFundsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchFundController extends AbstractController
{
    private SearchFundsUseCase $searchFundsUseCase;

    public function __construct(SearchFundsUseCase $searchFundsUseCase)
    {
        $this->searchFundsUseCase = $searchFundsUseCase;
    }

    #[Route('api/v1/search/fund', name: 'app_search_fund', methods: ['GET'])]
    public function __invoke(Request $request): APIResponse
    {
        try {
            $requestSearchFund = new RequestSearchFund($request->query->all());

            $funds = $this->searchFundsUseCase->execute($requestSearchFund);

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
