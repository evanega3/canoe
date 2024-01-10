<?php

declare(strict_types=1);

namespace App\Repository\Companies;

use App\Entity\Companies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Companies>
 *
 * @method Companies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Companies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Companies[]    findAll()
 * @method Companies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompaniesRepository extends ServiceEntityRepository
{
    final public const LIMIT = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Companies::class);
    }

    protected static function entityClass(): string
    {
        return Companies::class;
    }

    /**
     * @throws \Exception
     */
    public function findOneByIdOrFail(int $id): Companies
    {
        if (null === $company = $this->find($id)) {
            throw new \Exception(sprintf('Company with provided ID %d does not exists', $id), Response::HTTP_NOT_FOUND);
        }

        return $company;
    }

    public function save(object $company): void
    {
        $this->getEntityManager()->persist($company);
        $this->getEntityManager()->flush();
    }

    public function findByCriteria(array $criteria = [], array $orderBy = [], int $page = 1): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere('c.deleted_at IS NULL');

        foreach ($criteria as $key => $value) {
            $qb->andWhere(
                $qb->expr()->like("c.{$key}", ":$key")
            )->setParameter("$key", "%$value%");
        }

        foreach ($orderBy as $field => $order) {
            $qb->addOrderBy('c.'.$field, $order);
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
