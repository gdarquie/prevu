<?php
/**
 * Created by PhpStorm.
 * User: gaetan
 * Date: 03/03/2017
 * Time: 12:43
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pays
 *
 * @ORM\Table(name="ext_all")
 * @ORM\Entity
 */
class Extall
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_extall", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExtall;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=500, nullable=false)
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
     * @ORM\Column(name="type", type="string", length=500, nullable=true)
     */
    private $type;


    /**
     * @var string
     *
     * @ORM\Column(name="issues", type="integer", length=10, nullable=false)
     */
    private $issues;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", length=4, nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=500, nullable=true)
     */
    private $town;

    /**
     * @return int
     */
    public function getIdExtall()
    {
        return $this->idExtall;
    }

    /**
     * @param int $idExtall
     */
    public function setIdExtall($idExtall)
    {
        $this->idExtall = $idExtall;
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
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * @return string
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param string $issues
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
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





}

