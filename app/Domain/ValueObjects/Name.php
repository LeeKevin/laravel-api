<?php
namespace App\Domain\ValueObjects;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name extends ValueObject
{

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $firstname;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $lastname;


    /**
     * Create a Name object
     *
     * @param string $firstname
     * @param string $lastname
     * @return Name
     */
    public static function create($firstname, $lastname)
    {
        $name = new self();
        $name->firstname = $firstname;
        $name->lastname = $lastname;

        return $name;
    }
}