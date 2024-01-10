<?php

declare(strict_types=1);

namespace App\ApiResource\UseCases\Company;

use App\ApiResource\DTO\Company\RequestCreateCompanyData;
use App\Entity\Companies;
use App\Repository\Companies\CompaniesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class CreateCompanyUseCase
{
    private CompaniesRepository $companiesRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CompaniesRepository $companiesRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->companiesRepository = $companiesRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function execute(array $data): Companies
    {
        $requestCompanyData = new RequestCreateCompanyData($data);

        if ($this->companiesRepository->findOneBy(['name' => $requestCompanyData->getName()])) {
            throw new \Exception('Company already exists.', Response::HTTP_FORBIDDEN);
        }

        try {
            $this->entityManager->beginTransaction();

            $company = new Companies($requestCompanyData->getName());
            $this->companiesRepository->save($company);

            $this->entityManager->commit();

            return $company;
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}
