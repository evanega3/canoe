<?php

declare(strict_types=1);

namespace App\Controller\v1\Fund;

use App\ApiResource\Responses\APIResponse;
use App\ApiResource\UseCases\Fund\UpdateFundUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateFundController extends AbstractController
{
    private UpdateFundUseCase $updateFundUseCase;

    public function __construct(UpdateFundUseCase $updateFundUseCase)
    {
        $this->updateFundUseCase = $updateFundUseCase;
    }

    #[Route('api/v1/update/fund/{id}', name: 'app_update_fund', methods: ['PUT'])]
    public function __invoke(int $id, Request $request): APIResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $updatedFund = $this->updateFundUseCase->execute($id, $data);

            return new APIResponse(
                'Company has been updated.',
                $updatedFund->toArrayResponse(),
                [],
                Response::HTTP_OK
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
