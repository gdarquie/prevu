<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrAmazon
 *
 * @ORM\Table(name="amazon")
 * @ORM\Entity
 */
class PrAmazon
{
    /**
     * @var string
     *
     * @ORM\Column(name="tiny_image", type="string", length=45, nullable=true)
     */
    private $tinyImage;

    /**
     * @var string
     *
     * @ORM\Column(name="medium_image", type="string", length=45, nullable=true)
     */
    private $mediumImage;

    /**
     * @var string
     *
     * @ORM\Column(name="large_image", type="string", length=45, nullable=true)
     */
    private $largeImage;

    /**
     * @var string
     *
     * @ORM\Column(name="edito", type="text", length=65535, nullable=true)
     */
    private $edito;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_notice", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idNotice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;



    /**
     * Set tinyImage
     *
     * @param string $tinyImage
     *
     * @return PrAmazon
     */
    public function setTinyImage($tinyImage)
    {
        $this->tinyImage = $tinyImage;

        return $this;
    }

    /**
     * Get tinyImage
     *
     * @return string
     */
    public function getTinyImage()
    {
        return $this->tinyImage;
    }

    /**
     * Set mediumImage
     *
     * @param string $mediumImage
     *
     * @return PrAmazon
     */
    public function setMediumImage($mediumImage)
    {
        $this->mediumImage = $mediumImage;

        return $this;
    }

    /**
     * Get mediumImage
     *
     * @return string
     */
    public function getMediumImage()
    {
        return $this->mediumImage;
    }

    /**
     * Set largeImage
     *
     * @param string $largeImage
     *
     * @return PrAmazon
     */
    public function setLargeImage($largeImage)
    {
        $this->largeImage = $largeImage;

        return $this;
    }

    /**
     * Get largeImage
     *
     * @return string
     */
    public function getLargeImage()
    {
        return $this->largeImage;
    }

    /**
     * Set edito
     *
     * @param string $edito
     *
     * @return PrAmazon
     */
    public function setEdito($edito)
    {
        $this->edito = $edito;

        return $this;
    }

    /**
     * Get edito
     *
     * @return string
     */
    public function getEdito()
    {
        return $this->edito;
    }

    /**
     * Get idNotice
     *
     * @return integer
     */
    public function getIdNotice()
    {
        return $this->idNotice;
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
