<?php

declare(strict_types=1);

namespace App\EventListener\Fund;

use App\ApiResource\Responses\APIResponse;
use App\Entity\Funds;
use App\Repository\Funds\FundsRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\Response;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Funds::class)]
class FundEntityListener
{
    private FundsRepository $fundsRepository;

    public function __construct(FundsRepository $fundsRepository)
    {
        $this->fundsRepository = $fundsRepository;
    }

    public function prePersist(Funds $funds, PrePersistEventArgs $persistEventArgs): APIResponse
    {
        $entity = $persistEventArgs->getObject();

        if(!$entity instanceof Funds){
            $aliasesChecker = $this->fundsRepository->findAliases($funds->getAliases(), $funds->getManager()->getId());

            return new APIResponse(
                'Duplicated Fund.',
                $aliasesChecker,
                [],
                Response::HTTP_NOT_FOUND
            );
        }else{
            return new APIResponse(
                'Error.',
                [],
                [],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}