<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\ValueObjects\Name;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity()
 */
class User implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
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

    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    /**
     * Create new user object
     *
     * @param Name $name
     * @param string $email
     * @param string $password
     */
    public static function create(Name $name, $email, $password)
    {
        $user = new self($name);
        $user->email = $email;

        $user->setPassword(\Hash::make($password));
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
