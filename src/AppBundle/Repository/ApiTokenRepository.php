<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Entity\ApiToken;

class ApiTokenRepository extends EntityRepository
{
    /**
     * @param $token
     * @return ApiToken
     */
    public function findOneByToken($token)
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->findOneBy(array('token' => $token));

        return $apiToken;
    }

    /**
     * @param User $user
     * @return ApiToken[]
     */
    public function findAllForUser(User $user)
    {
        return $this->findBy(array('user' => $user->getId()));
    }
}
