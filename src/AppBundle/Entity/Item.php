<?php
/**
 * Created by PhpStorm.
 * User: gaetan
 * Date: 08/02/2017
 * Time: 16:00
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity
 */
class Item
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_item", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idItem;

    /**
     * @var \AppBundle\Entity\Books
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_book", referencedColumnName="id_book")
     * })
     */
    private $idbook;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $price;

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
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * @param int $idItem
     */
    public function setIdItem($idItem)
    {
        $this->idItem = $idItem;
    }

    /**
     * @return Books
     */
    public function getIdbook()
    {
        return $this->idbook;
    }

    /**
     * @param Books $idbook
     */
    public function setIdbook($idbook)
    {
        $this->idbook = $idbook;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @return mixed
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param mixed $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
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



}