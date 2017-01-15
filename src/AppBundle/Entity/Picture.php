<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 15:42
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="picture")
 */
class Picture extends BaseEntity
{
    /** @const */
    const PROFILE_PICTURE_LABEL = "profile_picture";
    /** @const */
    const COVER_PICTURE_LABEL = "cover_picture";
    /** @const */
    const REGULAR_PICTURE_LABEL = "regular_picture";

    /** @const */
    const CUSTOM_PICTURE_TYPE = "custom";
    /** @const */
    const DEFAULT_PICTURE_TYPE = "default";

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $path;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('profile_picture', 'cover_picture','regular_picture')", nullable=false, options={"unsigned":true, "default":"regular_picture"})
     */
    private $was;

    /** @ORM\Column(type="datetime", name="posted_at") */
    private $postedAt;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('custom', 'default')", nullable=false, options={"unsigned":true, "default":"default"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Profile", inversedBy="pictures", cascade={"all"})
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return Picture
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWas()
    {
        return $this->was;
    }

    /**
     * @param mixed $was
     * @return Picture
     */
    public function setWas($was)
    {
        $this->was = $was;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Picture
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     * @return Picture
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @param mixed $postedAt
     * @return Picture
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
        return $this;
    }
}