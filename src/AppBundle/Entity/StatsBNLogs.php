<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="stats_bn_logs")
 * @ORM\Entity
 */
class StatsBNLogs
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cb", type="string", length=45, nullable=true)
     */
    private $cb;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=45, nullable=true)
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="abonnement", type="string", length=45, nullable=true)
     */
    private $abonnement;

    /**
     * @var integer
     *
     * @ORM\Column(name="birthday", type="integer", length=4)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=45, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="csp", type="string", length=45, nullable=true)
     */
    private $csp;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="ean", type="integer", length=20)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=500, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=500, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="classification", type="string", length=500, nullable=true)
     */
    private $classification;

    /**
     * @var integer
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="hour", type="integer", length=2, nullable=true)
     */
    private $hour;

    /**
     * @var string
     *
     * @ORM\Column(name="day", type="string", length=45, nullable=true)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="day_short", type="integer", length=1, nullable=true)
     */
    private $day_short;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCb()
    {
        return $this->cb;
    }

    /**
     * @param string $cb
     */
    public function setCb($cb)
    {
        $this->cb = $cb;
    }

    /**
     * @return string
     */
    public function getAbonnement()
    {
        return $this->abonnement;
    }

    /**
     * @param string $abonnement
     */
    public function setAbonnement($abonnement)
    {
        $this->abonnement = $abonnement;
    }

    /**
     * @return int
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param int $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getCsp()
    {
        return $this->csp;
    }

    /**
     * @param string $csp
     */
    public function setCsp($csp)
    {
        $this->csp = $csp;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param string $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
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
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param string $localisation
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
    }

    /**
     * @return int
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param int $ean
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * @param string $classification
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;
    }

    /**
     * @return mixed
     */
    public function getDayShort()
    {
        return $this->day_short;
    }

    /**
     * @param mixed $day_short
     */
    public function setDayShort($day_short)
    {
        $this->day_short = $day_short;
    }





}
