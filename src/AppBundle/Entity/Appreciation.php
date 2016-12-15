<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 13:02
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="appreciation")
 */
class Appreciation extends BaseEntity
{
    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('like', 'dislike')")
     */
    private $status;

    /**
     * @var  User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="appreciations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var  Post
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="appreciations")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Appreciation
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Appreciation
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     * @return Appreciation
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }
}