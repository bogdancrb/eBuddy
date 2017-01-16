<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Relationship;
use Doctrine\ORM\EntityRepository;

class RelationshipRepository extends EntityRepository
{
	public function findByUserIdAndFriendId($userId, $friendId)
	{
		$result = $this->createQueryBuilder('r')
			->where('r.friend = :friend_id OR r.friend = :user_id')
			->andWhere('r.user = :user_id OR r.user = :friend_id')
			->setParameter('user_id', $userId)
			->setParameter('friend_id', $friendId)
			->getQuery()->getArrayResult();
		
		return $result;
	}

	public function findPendingFriendRequest($userId, $requesterId)
	{
		$result = $this->createQueryBuilder('r')
			->select('r.id')
			->where('r.user = :requester_id')
			->andWhere('r.friend = :user_id')
			->setParameter('requester_id', $requesterId)
			->setParameter('user_id', $userId)
			->getQuery()->getArrayResult();

		return $result[0]['id'];
	}

	public function addFriend(Relationship $relationship)
	{
		$this->getEntityManager()->persist($relationship);
		$this->getEntityManager()->flush();
	}
	
	public function findByUserId($userId)
	{
		$result = $this->createQueryBuilder('r')
			->select('ru.id as user_id')
			->addSelect('rup.firstName as profile_first_name')
			->addSelect('rup.lastName as profile_last_name')
			->addSelect('rupp.path')
			->join('r.user', 'ru')
			->join('ru.profile', 'rup')
			->join('rup.profilePicture', 'rupp')
			->where('r.friend = :user_id')
			->andWhere('r.status = :status')
			->setParameter('user_id', $userId)
			->setParameter('status', Relationship::STATUS_REQUEST_PENDING)
			->getQuery()->getArrayResult();

		return $result;
	}

	public function findFriendsByUserIdAndByStatus($user_id)
	{
		$resultFindByUser = $this->createQueryBuilder('r')
			->select('rf.id')
			->join('r.friend', 'rf')
			->where('r.user = :user_id')
			->andWhere('r.status = :status_pending OR r.status = :status_accepted')
			->setParameter('user_id', $user_id)
			->setParameter('status_pending', Relationship::STATUS_REQUEST_PENDING)
			->setParameter('status_accepted', Relationship::STATUS_REQUEST_ACCEPTED)
			->getQuery()->getArrayResult();

		$resultFindByFriend = $this->createQueryBuilder('r')
			->select('ru.id')
			->join('r.user', 'ru')
			->where('r.friend = :user_id')
			->andWhere('r.status = :status_pending OR r.status = :status_accepted')
			->setParameter('user_id', $user_id)
			->setParameter('status_pending', Relationship::STATUS_REQUEST_PENDING)
			->setParameter('status_accepted', Relationship::STATUS_REQUEST_ACCEPTED)
			->getQuery()->getArrayResult();

		$this->excludedFriendsIdResult($result, $resultFindByUser);
		$this->excludedFriendsIdResult($result, $resultFindByFriend);

		return $result;
	}

	private function excludedFriendsIdResult(&$result = [], $arr)
	{
		foreach($arr as $key => $value)
		{
			$result[] = $value['id'];
		}
	}

	public function updateStatus($friendRequestId, $status)
	{
		$result = $this->createQueryBuilder('r')
			->update()
			->set('r.status', '?1')
			->where('r.id = :relationship_id')
			->setParameter(1, $status)
			->setParameter('relationship_id', $friendRequestId)
			->getQuery()->getSingleScalarResult();

		return $result;
	}

	public function removeRelationshipById($friendRequestId)
	{
		$result = $this->createQueryBuilder('r')
			->delete()
			->where('r.id = :relationship_id')
			->setParameter('relationship_id', $friendRequestId)
			->getQuery()->getSingleScalarResult();

		return $result;
	}
}
