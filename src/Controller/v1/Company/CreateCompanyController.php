<?php

declare(strict_types=1);

namespace App\Controller\v1\Company;

use App\ApiResource\Responses\APIResponse;
use App\ApiResource\UseCases\Company\CreateCompanyUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateCompanyController extends AbstractController
{
    private CreateCompanyUseCase $createCompanyUseCase;

    public function __construct(CreateCompanyUseCase $createCompanyUseCase)
    {
        $this->createCompanyUseCase = $createCompanyUseCase;
    }

    #[Route('api/v1/create/company', name: 'app_create_company', methods: ['POST'])]
    public function __invoke(Request $request): APIResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $createdCompany = $this->createCompanyUseCase->execute($data);

            return new APIResponse(
                'Company has been created.',
                $createdCompany->toArrayResponse(),
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
