<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RelationshipRepository extends EntityRepository
{
	public function findFriendById($id)
	{
		$result = $this->createQueryBuilder('r')
			->where('friend_id = ?', $id)
			->getQuery()->getResult();

		var_dump($result);
		die();
	}
}
