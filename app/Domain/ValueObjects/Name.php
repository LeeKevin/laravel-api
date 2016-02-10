<?php
namespace App\Domain\ValueObjects;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name
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


    public function __construct()
    {
    }

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
        $name->setFirstname($firstname);
        $name->setLastname($lastname);

        return $name;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
}