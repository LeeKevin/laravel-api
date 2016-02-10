<?php
namespace App\Infrastructure\Doctrine;

use App\Domain\Repositories\UserRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{

    public function all($orderField = 'id', $order = 'ASC')
    {
        // implement your find by title method
    }
}