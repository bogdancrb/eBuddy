<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 11:53
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseEntity
{
    /**
     * @var  Account
     * @ORM\OneToOne(targetEntity="Account",cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;

    /**
     * @var  Profile
     * @ORM\OneToOne(targetEntity="Profile",cascade={"persist"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @var  User[]
     *
     * The people who I think are my friends.
     *
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="user")
     */
    private $friends;

    /**
     * @var  User[]
     *
     * The people who think that I’m their friend.
     *
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="friend")
     */
    private $friendsWithMe;

    /**
     * @var  Appreciation[]
     * @ORM\OneToMany(targetEntity="Appreciation", mappedBy="user")
     */
    private $appreciations;

    /**
     * @var  Comment[]
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return User
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     * @return User
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param User[] $friends
     * @return User
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * @param User[] $friendsWithMe
     * @return User
     */
    public function setFriendsWithMe($friendsWithMe)
    {
        $this->friendsWithMe = $friendsWithMe;
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
     * @return User
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
     * @return User
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    function __toString()
    {
       return "User";
    }
}