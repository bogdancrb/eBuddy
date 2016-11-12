<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 16:14
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 */
class Friendship
{
    /**
     * @var  User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friends")
     * @ORM\Id
     */
    private $user;

    /**
     * @var  User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\Id
     */
    private $friend;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('blocked', 'friend')")
     */
    private $status;

}