<?php
namespace App\Domain\Repositories;
interface UserRepository
{

    /**
     * Get all Users
     *
     * @param string $orderField
     * @param string $order
     *
     * @return \App\Domain\Entities\User[]
     */
    public function all($orderField = 'id', $order = 'ASC');

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return \App\Domain\Entities\User
     */
    public function find($id);

    /**
     * Finds one or more entities by a field.
     *
     * @param array $criteria An associative array containing the fields and values to search on.
     *
     * @return \App\Domain\Entities\User
     */
    public function findBy(array $criteria);
}