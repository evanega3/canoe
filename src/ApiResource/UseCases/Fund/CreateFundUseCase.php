<?php

declare(strict_types=1);

namespace App\ApiResource\UseCases\Fund;

use App\ApiResource\DTO\Fund\RequestCreateFundData;
use App\ApiResource\Exceptions\CustomException;
use App\ApiResource\Responses\APIResponse;
use App\Entity\Funds;
use App\Repository\Companies\CompaniesRepository;
use App\Repository\Funds\FundsRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class CreateFundUseCase
{
    private FundsRepository $fundsRepository;
    private CompaniesRepository $companiesRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FundsRepository $fundsRepository,
        CompaniesRepository $companiesRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->fundsRepository = $fundsRepository;
        $this->companiesRepository = $companiesRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function execute(array $data): Funds|APIResponse
    {
        $requestFundData = new RequestCreateFundData($data);

        if($this->fundsRepository->findOneBy(['name' => $requestFundData->getName()])){
            throw new Exception('Fund already exists.', Response::HTTP_FORBIDDEN);
        }

        if($aliases = $this->fundsRepository->findAliases($requestFundData->getAliases(), $requestFundData->getManager())){
            $exception = new CustomException('Duplicated Fund', Response::HTTP_FORBIDDEN);
            $exception->setData(json_encode($aliases));

            throw $exception;
        }

        try {
            $this->entityManager->beginTransaction();

            $manager = $this->companiesRepository->findOneByIdOrFail($requestFundData->getManager());

            $fund = new Funds(
                $requestFundData->getName(),
                $manager,
                $requestFundData->getAliases()
            );

            $fund->setStartYear(new \DateTime($requestFundData->getStartYear()));
            $this->fundsRepository->save($fund);
            $this->entityManager->commit();

            return $fund;
        }catch (Exception $exception){
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}