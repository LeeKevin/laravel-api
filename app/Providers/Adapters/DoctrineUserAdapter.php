<?php

namespace App\Providers\Adapters;

use App\Domain\Repositories\UserRepository;
use Tymon\JWTAuth\Providers\User\UserInterface;

class DoctrineUserAdapter implements UserInterface
{

    /**
     * @var \App\Domain\Entities\User
     */
    protected $user;

    /**
     * Create a new User instance
     *
     * @param \App\Domain\Entities\User $user
     * @param \App\Domain\Repositories\UserRepository $repository
     */
    public function __construct(\App\Domain\Entities\User $user, UserRepository $repository)
    {
        $this->user = $user;
        $this->repository = $repository;
    }

    /**
     * Get the user by the given key, value
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return \App\Domain\Entities\User
     */
    public function getBy($key, $value)
    {
        return $this->user->where($key, $value)->first();
    }
}
