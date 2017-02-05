<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Borrower
 *
 * @ORM\Table(name="borrower")
 * @ORM\Entity
 */
class Borrower
{
    /**
     * @var integer
     *
     * @ORM\Column(name="yearofbirth", type="integer", nullable=true)
     */
    private $yearofbirth;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_borrower", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBorrower;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;

    /**
     * @return int
     */
    public function getYearofbirth()
    {
        return $this->yearofbirth;
    }

    /**
     * @param int $yearofbirth
     */
    public function setYearofbirth($yearofbirth)
    {
        $this->yearofbirth = $yearofbirth;
    }


    /**
     * Get idBorrower
     *
     * @return integer
     */
    public function getIdBorrower()
    {
        return $this->idBorrower;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param mixed $date_creation
     */
    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * @param mixed $last_update
     */
    public function setLastUpdate($last_update)
    {
        $this->last_update = $last_update;
    }


}
