<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 13:33
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 * @ORM\Table(name="profile")
 */
class Profile extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, name="first_name")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false, name="last_name")
     * */
    private $lastName;

    /**
     * @var Picture
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Picture", cascade={"all"})
     * @ORM\JoinColumn(name="profile_picture_id", referencedColumnName="id")
     */
    private $profilePicture;

    /**
     * @var Picture
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Picture", cascade={"all"})
     * @ORM\JoinColumn(name="cover_picture_id", referencedColumnName="id")
     */
    private $coverPicture;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address", cascade={"all"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", name="last_change")
     */
    private $lastChange;

    /**
     * @var  User
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", mappedBy="profile")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Picture", mappedBy="author", cascade={"all"})
     */
    private $pictures;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return Picture
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param Picture $profilePicture
     * @return Profile
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    /**
     * @return Picture
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * @param Picture $coverPicture
     * @return Profile
     */
    public function setCoverPicture($coverPicture)
    {
        $this->coverPicture = $coverPicture;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return Profile
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }

    /**
     * @param string $lastChange
     * @return Profile
     */
    public function setLastChange($lastChange)
    {
        $this->lastChange = $lastChange;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Profile
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param mixed $pictures
     * @return Profile
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
        return $this;
    }
}