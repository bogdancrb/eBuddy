<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 13:18
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Acl\Exception\Exception;

class CommentsRepository extends EntityRepository
{
    /**
     * @param Comment $p
     */
    public function saveCommant(Post $p)
    {
        $this->getEntityManager()->persist($p);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $userId
     * @return array
     */
    public function getAllUserCommentsFromAPost($userId, $postId)
    {
        /** @var array */
        $comments = $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :author_id')
            ->join('c.post', 'p')
            ->where('p.id = :postId')
            ->setParameter(':postId', $postId)
            ->setParameter(':author_id', $userId)
            ->orderBy('c.postedAt', 'DESC')
            ->getQuery()->getArrayResult();

        return $comments;
    }

    /**
     * Offset and limit is for dynamic loading
     *
     * @param $userId
     * @param $postId
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getAllUserCommentsFromAPostWithLimitAndOffset($userId, $postId, $limit, $offset)
    {
        /** @var array */
        $comments = $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :author_id')
            ->join('c.post', 'p')
            ->where('p.id = :postId')
            ->setParameter('postId', $postId)
            ->setParameter('author_id', $userId)
            ->setMaxResults( $limit )
            ->setFirstResult( $offset )
            ->orderBy('c.postedAt', 'DESC')
            ->getQuery()->getArrayResult();

        return $comments;
    }

    /**
     * Offset and limit is for dynamic loading
     *
     * @param $postId
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getAllPostCommentsWithLimitAndOffset($postId, $limit, $offset)
    {
        /** @var array */
        $comments = $this->createQueryBuilder('c')
            ->join('c.post', 'p')
            ->where('p.id = :postId')
            ->setParameter('postId', $postId)
            ->setMaxResults( $limit )
            ->setFirstResult( $offset )
            ->orderBy('c.postedAt', 'DESC')
            ->getQuery()->getArrayResult();

        return $comments;
    }

    /**
     * @param $postId
     * @return Comment
     */
    public function getLastCommentOfAPost($postId)
    {
        /** @var array */
        $comment = $this->createQueryBuilder('c')
            ->join('c.post', 'p')
            ->where('p.id = 5')
            ->orderBy('c.postedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getArrayResult();

        return $comment;
    }
}