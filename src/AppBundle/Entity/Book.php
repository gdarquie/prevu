<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity
 */
class Book
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_book", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBook;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=16777215, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="text", length=16777215, nullable=true)
     */
    private $author;

    /**
     * @var integer
     *
     * @ORM\Column(name="publicationyear", type="integer", length=4, nullable=true)
     */
    private $publicationyear;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=45, nullable=true)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="cdu", type="string", length=45, nullable=true)
     */
    private $cdu;

    /**
     * @var string
     *
     * @ORM\Column(name="dewey", type="string", length=45, nullable=true)
     */
    private $dewey;

    /**
     * @var integer
     *
     * @ORM\Column(name="issues", type="integer", nullable=true)
     */
    private $issues;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_issues", type="integer", nullable=true)
     */
    private $totalIssues;

    /**
     * @var integer
     *
     * @ORM\Column(name="renewals", type="integer", nullable=true)
     */
    private $renewals;

    /**
     * @var string
     *
     * @ORM\Column(name="work", type="string", length=255, nullable=true)
     */
    private $work;

    /**
     * @var string
     *
     * @ORM\Column(name="work_title", type="string", length=16777215, nullable=true)
     */
    private $workTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="work_author", type="string", length=16777215, nullable=true)
     */
    private $workAuthor;


    /**
     * @var \AppBundle\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_country", referencedColumnName="id_country")
     * })
     */
    private $idCountry;

    /**
     * @var \AppBundle\Entity\Itemtype
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Itemtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_itemtype", referencedColumnName="id_itemtype")
     * })
     */
    private $iditemtype;

    /**
     * @var \AppBundle\Entity\Code
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Code")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_code", referencedColumnName="id_code")
     * })
     */
    private $idcode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Language", inversedBy="books")
     * @ORM\JoinTable(name="book_language",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_book", referencedColumnName="id_book")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_language", referencedColumnName="id_language")
     *   }
     * )
     */
    private $languages;


    /**
     * @var \AppBundle\Entity\Author
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Author", inversedBy="books")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_author", referencedColumnName="id_author")
     * })
     */
    private $first_author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return int
     */
    public function getPublicationyear()
    {
        return $this->publicationyear;
    }

    /**
     * @param int $publicationyear
     */
    public function setPublicationyear($publicationyear)
    {
        $this->publicationyear = $publicationyear;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getCdu()
    {
        return $this->cdu;
    }

    /**
     * @param string $cdu
     */
    public function setCdu($cdu)
    {
        $this->cdu = $cdu;
    }

    /**
     * @return string
     */
    public function getDewey()
    {
        return $this->dewey;
    }

    /**
     * @param string $dewey
     */
    public function setDewey($dewey)
    {
        $this->dewey = $dewey;
    }

    /**
     * @return int
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param int $issues
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
    }

    /**
     * @return int
     */
    public function getTotalIssues()
    {
        return $this->totalIssues;
    }

    /**
     * @param int $totalIssues
     */
    public function setTotalIssues($totalIssues)
    {
        $this->totalIssues = $totalIssues;
    }

    /**
     * @return int
     */
    public function getRenewals()
    {
        return $this->renewals;
    }

    /**
     * @param int $renewals
     */
    public function setRenewals($renewals)
    {
        $this->renewals = $renewals;
    }

    /**
     * @return string
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @param string $work
     */
    public function setWork($work)
    {
        $this->work = $work;
    }

    /**
     * @return string
     */
    public function getWorkTitle()
    {
        return $this->workTitle;
    }

    /**
     * @param string $workTitle
     */
    public function setWorkTitle($workTitle)
    {
        $this->workTitle = $workTitle;
    }

    /**
     * @return string
     */
    public function getWorkAuthor()
    {
        return $this->workAuthor;
    }

    /**
     * @param string $workAuthor
     */
    public function setWorkAuthor($workAuthor)
    {
        $this->workAuthor = $workAuthor;
    }

    /**
     * @return int
     */
    public function getIdBook()
    {
        return $this->idBook;
    }

    /**
     * @param int $idBook
     */
    public function setIdBook($idBook)
    {
        $this->idBook = $idBook;
    }

    /**
     * @return Country
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }

    /**
     * @param Country $idCountry
     */
    public function setIdCountry($idCountry)
    {
        $this->idCountry = $idCountry;
    }

    /**
     * @return Itemtype
     */
    public function getIditemtype()
    {
        return $this->iditemtype;
    }

    /**
     * @param Itemtype $iditemtype
     */
    public function setIditemtype($iditemtype)
    {
        $this->iditemtype = $iditemtype;
    }

    /**
     * @return Code
     */
    public function getIdcode()
    {
        return $this->idcode;
    }

    /**
     * @param Code $idcode
     */
    public function setIdcode($idcode)
    {
        $this->idcode = $idcode;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return Author
     */
    public function getFirstAuthor()
    {
        return $this->first_author;
    }

    /**
     * @param Author $first_author
     */
    public function setFirstAuthor($first_author)
    {
        $this->first_author = $first_author;
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
