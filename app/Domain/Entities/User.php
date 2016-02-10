<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Name;
use App\Infrastructure\Doctrine\DoctrineEntity;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable;

/**
 * @ORM\Entity()
 */
class User extends DoctrineEntity implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Timestamps, Authorizable, CanResetPassword;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;
    /**
     * The name value object which holds the
     * first and last name of the user
     * @ORM\Embedded(class="App\Domain\ValueObjects\Name", columnPrefix=false)
     *
     * @var Name
     */
    protected $name;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $email;

    public function setPassword($password)
    {
        $this->password = \Hash::make($password);

        return $this;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
