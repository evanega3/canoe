<?php

declare(strict_types=1);

namespace App\Repository\Funds;

use App\Entity\Funds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Funds>
 *
 * @method Funds|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funds|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funds[]    findAll()
 * @method Funds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundsRepository extends ServiceEntityRepository
{
    final public const LIMIT = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funds::class);
    }

    public function findOneByIdOrFail(int $id): Funds
    {
        if (null === $fund = $this->find($id)) {
            throw new \Exception(sprintf('Fund with provided ID %d does not exists', $id));
        }

        return $fund;
    }

    public function findAliases(array $aliases, int $manager)
    {
        $search = $this->createQueryBuilder('f');

        foreach ($aliases as $alias){
            $search = $search->orWhere('f.aliases LIKE :alias AND f.manager = :manager')
                ->setParameter('alias', '%'.$alias.'%')
                ->setParameter('manager', $manager);
        }

        return $search
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(object $fund): void
    {
        $this->getEntityManager()->persist($fund);
        $this->getEntityManager()->flush();
    }

    public function findByCriteria(array $criteria = [], array $orderBy = [], int $page = 1): array
    {
        $qb = $this->createQueryBuilder('f');

        $qb->andWhere('f.deleted_at IS NULL');

        foreach ($criteria as $key => $value) {
            $qb->andWhere(
                $qb->expr()->like("f.{$key}", ":$key")
            )->setParameter("$key", "%$value%");
        }

        foreach ($orderBy as $field => $order) {
            $qb->addOrderBy('f.'.$field, $order);
        }

        $offset = ($page - 1) * self::LIMIT;
        $qb->setMaxResults(self::LIMIT)->setFirstResult($offset);

        $query = $qb->getQuery();
        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return [
            'count' => count($paginator),
            'resultSet' => $query->execute(),
        ];
    }
}
