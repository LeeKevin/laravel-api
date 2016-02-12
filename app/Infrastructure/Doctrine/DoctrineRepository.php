<?php
namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository extends EntityRepository
{

    /**
     *
     * The DoctrineRepository serves as a base class for all entity repositories
     * to provide business logic that is common between all the repositories.
     * Entity-specific logic will go in that entity's repository class.
     *
     **/


    /**
     * Retrieve all entities, sort them accordingly
     *
     * @param string $orderField
     * @param string $order
     * @return array
     */
    public function all($orderField = 'id', $order = 'ASC')
    {
        return $this->findBy([], [$orderField => $order]);
    }
}