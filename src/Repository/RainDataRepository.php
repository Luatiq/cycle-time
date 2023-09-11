<?php

namespace App\Repository;

use App\Entity\RainData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RainData>
 *
 * @method RainData|null find($id, $lockMode = null, $lockVersion = null)
 * @method RainData|null findOneBy(array $criteria, array $orderBy = null)
 * @method RainData[]    findAll()
 * @method RainData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RainDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RainData::class);
    }

    public function save(RainData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(RainData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
