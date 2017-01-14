<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 12:11
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostsRepository")
 */
class Post extends BaseEntity
{
    /**
     * @var  User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('Friends', 'All')")
     */
    private $onlyFor;

    /**
     * @var  Appreciation[]
     * @ORM\OneToMany(targetEntity="Appreciation", mappedBy="post")
     */
    private $appreciations;

    /**
     * @var  Comment[]
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOnlyFor()
    {
        return $this->onlyFor;
    }

    /**
     * @param mixed $onlyFor
     * @return Post
     */
    public function setOnlyFor($onlyFor)
    {
        $this->onlyFor = $onlyFor;
        return $this;
    }

    /**
     * @return Appreciation[]
     */
    public function getAppreciations()
    {
        return $this->appreciations;
    }

    /**
     * @param Appreciation[] $appreciations
     * @return Post
     */
    public function setAppreciations($appreciations)
    {
        $this->appreciations = $appreciations;
        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     * @return Post
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
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
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

}