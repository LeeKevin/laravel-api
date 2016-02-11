<?php
namespace App\Domain\ValueObjects;

use App\Domain\Object;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name extends Object
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

    /**
     * Validation rules for the Name object
     *
     * @return array
     */
    protected function rules() {
        return [
            'firstname' => 'required|alpha_dash',
            'lastname'  => 'required|alpha_dash',
        ];
    }
}