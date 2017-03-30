<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Thesaurus
 *
 * @ORM\Table(name="thesaurus")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ThesaurusRepository")
 */
class Thesaurus
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id_thesaurus", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=500)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=500, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=65535, nullable=true)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;


    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=500, nullable=true)
     */
    private $category;


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

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function __toString()
    {
        return $this->getTitle();
    }



}
