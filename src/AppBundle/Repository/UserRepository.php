<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param $email
     * @return User
     */
    public function findUserByEmail($email)
    {
        /** @var User $user */
        $user = $this->createQueryBuilder('a')
            ->join('a.account', 'p')
            // Join 'Adjudicacion' to 'CursoAcademico'
            ->where('p.email = :profile_email')
            // match id of joined `CursoAcademico`
            ->setParameter('profile_email', $email)
            ->getQuery()->getSingleResult();

        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    public function loadUserByUsername($email)
    {
        /** @var User $user */
        $user = $this->findUserByEmail($email);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $email));
        }

        return $user;
    }
}
