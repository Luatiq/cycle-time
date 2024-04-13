<?php

namespace App\Repository;

use App\Entity\Period;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Period>
 *
 * @method Period|null find($id, $lockMode = null, $lockVersion = null)
 * @method Period|null findOneBy(array $criteria, array $orderBy = null)
 * @method Period[]    findAll()
 * @method Period[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Period::class);
    }

    public function save(Period $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Period $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRemindersDue(
        \DateTime|DateTimeImmutable $start,
    ): array {
        return $this->createQueryBuilder('x')
            ->andWhere('x.days IS NULL OR FIND_IN_SET(:day, x.days) > 0')
            ->setParameter('day', (new \DateTime())->format('N'))
            ->andWhere('SUBTIME(x.startTime, x.hoursBeforeStartWarning) >= :start OR SUBTIME(x.startTime, x.hoursBeforeStartWarning) <= :start')
            ->setParameter(':start', $start)
            ->getQuery()
            ->getResult();
    }
}
