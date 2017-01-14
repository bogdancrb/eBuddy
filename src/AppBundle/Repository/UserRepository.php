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
            ->getQuery()->getOneOrNullResult();

        return $user;
    }

    public function loadUserByUsername($username) {
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username or u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = print_r(
                'Unable to find an active admin AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }
        return $user;
    }
}
