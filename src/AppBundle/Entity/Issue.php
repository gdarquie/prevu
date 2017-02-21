<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issue
 *
 * @ORM\Table(name="issue")
 * @ORM\Entity
 */
class Issue
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_issue", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idIssue;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedue", type="date", nullable=true)
     */
    private $datedue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issuedate", type="date", nullable=true)
     */
    private $issuedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="returndate", type="date", nullable=true)
     */
    private $returndate;

    /**
     * @var integer
     *
     * @ORM\Column(name="renewals", type="integer", nullable=true)
     */
    private $renewals;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=45, nullable=true)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="ufr", type="string", length=45, nullable=true)
     */
    private $ufr;

    /**
     * @var string
     *
     * @ORM\Column(name="etape", type="string", length=45, nullable=true)
     */
    private $etape;

    /**
     * @var \AppBundle\Entity\Borrower
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Borrower")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_borrower", referencedColumnName="id_borrower")
     * })
     */
    private $idborrower;

    /**
     * @var \AppBundle\Entity\Book
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_book", referencedColumnName="id_book")
     * })
     */
    private $idbook;

    /**
     * @var \AppBundle\Entity\Library
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Library")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_library", referencedColumnName="id_library")
     * })
     */
    private $idlibrary;

    /**
     * @var string
     *
     * @ORM\Column(name="koha_borrower", type="string")
     */
    private $koha_borrower;

    /**
     * @var string
     *
     * @ORM\Column(name="koha_item", type="string")
     */
    private $koha_item;



    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;


    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return PrIssues
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set datedue
     *
     * @param \DateTime $datedue
     *
     * @return PrIssues
     */
    public function setDatedue($datedue)
    {
        $this->datedue = $datedue;

        return $this;
    }

    /**
     * Get datedue
     *
     * @return \DateTime
     */
    public function getDatedue()
    {
        return $this->datedue;
    }

    /**
     * Set issuedate
     *
     * @param \DateTime $issuedate
     *
     * @return PrIssues
     */
    public function setIssuedate($issuedate)
    {
        $this->issuedate = $issuedate;

        return $this;
    }

    /**
     * Get issuedate
     *
     * @return \DateTime
     */
    public function getIssuedate()
    {
        return $this->issuedate;
    }

    /**
     * Set returndate
     *
     * @param \DateTime $returndate
     *
     * @return PrIssues
     */
    public function setReturndate($returndate)
    {
        $this->returndate = $returndate;

        return $this;
    }

    /**
     * Get returndate
     *
     * @return \DateTime
     */
    public function getReturndate()
    {
        return $this->returndate;
    }

    /**
     * Set renewals
     *
     * @param integer $renewals
     *
     * @return PrIssues
     */
    public function setRenewals($renewals)
    {
        $this->renewals = $renewals;

        return $this;
    }

    /**
     * Get renewals
     *
     * @return integer
     */
    public function getRenewals()
    {
        return $this->renewals;
    }

    /**
     * Set niveau
     *
     * @param string $niveau
     *
     * @return PrIssues
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set ufr
     *
     * @param string $ufr
     *
     * @return PrIssues
     */
    public function setUfr($ufr)
    {
        $this->ufr = $ufr;

        return $this;
    }

    /**
     * Get ufr
     *
     * @return string
     */
    public function getUfr()
    {
        return $this->ufr;
    }

    /**
     * Set etape
     *
     * @param string $etape
     *
     * @return PrIssues
     */
    public function setEtape($etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return string
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Get idIssue
     *
     * @return integer
     */
    public function getIdIssue()
    {
        return $this->idIssue;
    }

    /**
     * @return Borrower
     */
    public function getIdborrower()
    {
        return $this->idborrower;
    }

    /**
     * @param Borrower $idborrower
     */
    public function setIdborrower($idborrower)
    {
        $this->idborrower = $idborrower;
    }


    /**
     * Set idbook
     *
     * @param \AppBundle\Entity\Books $idbook
     *
     * @return PrIssues
     */
    public function setIdbook(\AppBundle\Entity\Books $idbook = null)
    {
        $this->idbook = $idbook;

        return $this;
    }

    /**
     * Get idbook
     *
     * @return \AppBundle\Entity\Books
     */
    public function getIdbook()
    {
        return $this->idbook;
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
    public function getIdlibrary()
    {
        return $this->idlibrary;
    }

    /**
     * @param mixed $idlibrary
     */
    public function setIdlibrary($idlibrary)
    {
        $this->idlibrary = $idlibrary;
    }

    /**
     * @return string
     */
    public function getKohaBorrower()
    {
        return $this->koha_borrower;
    }

    /**
     * @param string $koha_borrower
     */
    public function setKohaBorrower($koha_borrower)
    {
        $this->koha_borrower = $koha_borrower;
    }

    /**
     * @return string
     */
    public function getKohaItem()
    {
        return $this->koha_item;
    }

    /**
     * @param string $koha_item
     */
    public function setKohaItem($koha_item)
    {
        $this->koha_item = $koha_item;
    }



}
