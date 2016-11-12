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
 * @ORM\Entity
 * @ORM\Table(name="post")
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
}