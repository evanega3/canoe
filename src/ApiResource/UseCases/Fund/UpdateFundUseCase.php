<?php

declare(strict_types=1);

namespace App\ApiResource\UseCases\Fund;

use App\ApiResource\DTO\Fund\RequestUpdateFundData;
use App\ApiResource\Exceptions\CustomException;
use App\Entity\Funds;
use App\Repository\Companies\CompaniesRepository;
use App\Repository\Funds\FundsRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class UpdateFundUseCase
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
    public function execute(int $id, array $data): Funds
    {
        $requestFundData = new RequestUpdateFundData($data);

        if($this->fundsRepository->findOneBy(['name' => $requestFundData->getName()])){
            throw new Exception('Fund already exists. Use another name.', Response::HTTP_FORBIDDEN);
        }

        if($aliases = $this->fundsRepository->findAliases($requestFundData->getAliases(), $requestFundData->getFundManager())){
            $exception = new CustomException('Duplicated Fund', Response::HTTP_FORBIDDEN);
            $exception->setData(json_encode($aliases));

            throw $exception;
        }

        $fund = $this->fundsRepository->findOneByIdOrFail($id);

        try {
            $manager = $this->companiesRepository->findOneByIdOrFail($requestFundData->getFundManager());
            $requestFundData->setManager($manager);

            $this->updateFund($fund, $requestFundData);

            return $fund;
        }catch (Exception $exception){
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    private function updateFund(Funds $fund, RequestUpdateFundData $requestUpdateFundData): void
    {
        $fund->updateFromRequestFundData($requestUpdateFundData);

        $this->fundsRepository->save($fund);
    }
}