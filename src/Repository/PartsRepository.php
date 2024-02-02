<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Parts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parts>
 *
 * @method Parts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parts[]    findAll()
 * @method Parts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parts::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['price' => 'ASC', 'name' => 'ASC']);
    }



}
