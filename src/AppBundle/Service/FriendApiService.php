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
		$relationship = new Relationship();
		$relationship->setId(1);

		$this->getEntityManager()->getRepository('AppBundle:Relationship')->findFriendById($relationship->getId());
	}
}