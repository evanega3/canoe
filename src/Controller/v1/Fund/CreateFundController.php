<?php

declare(strict_types=1);

namespace App\Controller\v1\Fund;

use App\ApiResource\Responses\APIResponse;
use App\ApiResource\UseCases\Fund\CreateFundUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateFundController extends AbstractController
{
    private CreateFundUseCase $createFundUseCase;

    public function __construct(CreateFundUseCase $createFundUseCase)
    {
        $this->createFundUseCase = $createFundUseCase;
    }

    #[Route('api/v1/create/fund', name: 'app_create_fund', methods: ['POST'])]
    public function __invoke(Request $request): APIResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $createdFund = $this->createFundUseCase->execute($data);

            return new APIResponse(
                'Fund has been created.',
                $createdFund->toArrayResponse(),
                [],
                Response::HTTP_CREATED
            );
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
