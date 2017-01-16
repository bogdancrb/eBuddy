<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="relationship")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RelationshipRepository")
 */
class Relationship extends BaseEntity
{
	const STATUS_REQUEST_PENDING = 'pending';
	const STATUS_REQUEST_ACCEPTED = 'accepted';
	
	/**
	 * @var  User
	 * @ORM\OneToOne(targetEntity="User", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;

	/**
	 * @var  User
	 * @ORM\OneToOne(targetEntity="User", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(name="friend_id", referencedColumnName="id")
	 */
	private $friend;

	/**
	 * @ORM\Column(type="string", columnDefinition="ENUM('pending', 'accepted')")
	 */
	private $status;

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return User
	 */
	public function getFriend()
	{
		return $this->friend;
	}

	
}