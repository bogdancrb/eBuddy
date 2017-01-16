<?php
/**
 * Created by PhpStorm.
 * User: Bogdan
 * Date: 14.01.2017
 * Time: 18:39
 */

namespace AppBundle\Service;

use AppBundle\Entity\Relationship;

class FriendApiService extends BaseService
{
	const SERVICE_NAME = 'api.friend.service';
	
	public function sendFriendRequest($data)
	{
		$isAlreadyFriend = $this->getEntityManager()->getRepository('AppBundle:Relationship')->findByUserIdAndFriendId(
			$this->getLoggedUser()->getId(),
			$data['friend_id']
		);

		if (empty($isAlreadyFriend))
		{
			$friend = $this->getEntityManager()->getRepository('AppBundle:User')->findOneBy(array('id' => $data['friend_id']));

			$relationship = new Relationship();
			$relationship->setUser($this->getLoggedUser())
				->setFriend($friend)
				->setStatus(Relationship::STATUS_REQUEST_PENDING);

			$this->getEntityManager()->getRepository('AppBundle:Relationship')->addFriend($relationship);
		}
	}

	public function getFriendRequests()
	{
		$result = $this->getEntityManager()->getRepository('AppBundle:Relationship')->findByUserId($this->getLoggedUser()->getId());

		return $result;
	}
	
	public function acceptFriendRequest($data)
	{
		$pendingRelationshipId = $this->getEntityManager()->getRepository('AppBundle:Relationship')->findPendingFriendRequest(
			$this->getLoggedUser()->getId(),
			$data['friend_id']
		);

		if (!empty($pendingRelationshipId))
		{
			$result = $this->getEntityManager()->getRepository('AppBundle:Relationship')->updateStatus(
				$pendingRelationshipId,
				Relationship::STATUS_REQUEST_ACCEPTED
			);

			return $result;
		}

		return false;
	}
}