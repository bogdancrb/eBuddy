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
     * Offset and limit is for dynamic loading
     *
     * @return array | Profile[]
     */
    public function getAllProfiles()
    {
        /** @var array */
        $profiles = $this->createQueryBuilder('c')
            ->getQuery()->getResult();

        return $profiles;
    }
}