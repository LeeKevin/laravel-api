<?php
namespace App\Infrastructure\Doctrine;

use App\Domain\Repositories\UserRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{

    /**
     * Retrieve all user entities
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