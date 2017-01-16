<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 13-Jan-17
 * Time: 18:47
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;

class ProfileRepository extends EntityRepository
{
    /**
     * @param $currentUserProfileId
     * @param array $excludedFriendsIds
     * @return \AppBundle\Entity\Profile[]|array
     */
    public function getAllProfiles($currentUserProfileId, $excludedFriendsIds = [])
    {
        $excludedFriendsIds = !empty($excludedFriendsIds) ? $excludedFriendsIds : 0;

        $profiles = $this->createQueryBuilder('p');

        $query = $profiles->join('p.user', 'pu')
            ->andWhere('p.id != :profile_id')
            ->andWhere($profiles->expr()->notIn('pu.id', ':excluded'))
            ->setParameter('profile_id', $currentUserProfileId)
            ->setParameter('excluded', $excludedFriendsIds)
            ->getQuery()->getResult();

        return $query;
    }
}