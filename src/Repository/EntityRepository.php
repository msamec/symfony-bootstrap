<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class EntityRepository extends ServiceEntityRepository
{
    /**
     * Save entity (create)
     *
     * @param EntityInterface $entity
     * @param boolean $flush
     */
    public function save(EntityInterface $entity, $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param EntityInterface $entity
     * @param boolean $flush
     */
    public function remove(EntityInterface $entity, $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}