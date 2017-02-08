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
     * @var string
     *
     * @ORM\Column(name="koha", type="string")
     */
    private $koha;

    /**
     * @var \AppBundle\Entity\Library
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Library")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="library", referencedColumnName="id_library")
     * })
     */
    private $library;

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

    /**
     * @return string
     */
    public function getKoha()
    {
        return $this->koha;
    }

    /**
     * @param string $koha
     */
    public function setKoha($koha)
    {
        $this->koha = $koha;
    }

    /**
     * @return Library
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param Library $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
    }


}
