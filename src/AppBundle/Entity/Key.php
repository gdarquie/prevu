<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Key
 *
 * @ORM\Table(name="key")
 * @ORM\Entity
 */
class Key
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_key", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idKey;

    /**
     * @var string
     *
     * @ORM\Column(name="koha", type="string")
     */
    private $koha;

    /**
     * @var \AppBundle\Entity\Book
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prevu", referencedColumnName="id_book")
     * })
     */
    private $prevu;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

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
    public function getIdKey()
    {
        return $this->idKey;
    }

    /**
     * @param int $idKey
     */
    public function setIdKey($idKey)
    {
        $this->idKey = $idKey;
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
     * @return Book
     */
    public function getPrevu()
    {
        return $this->prevu;
    }

    /**
     * @param Book $prevu
     */
    public function setPrevu($prevu)
    {
        $this->prevu = $prevu;
    }


    /**
     * @return \DateTime
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param \DateTime $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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