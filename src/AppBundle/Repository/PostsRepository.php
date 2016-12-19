<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 13:18
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;

class PostsRepository extends EntityRepository
{
    /**
     * @param Post $p
     */
    public function savePost(Post $p)
    {
        $this->getEntityManager()->persist($p);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $userId
     * @return array
     */
    public function getAllUserPosts($userId)
    {
        /** @var array */
        $posts = $this->createQueryBuilder('p')
            ->join('p.author', 'a')
            ->where('a.id = :author_id')
            ->setParameter('author_id', $userId)
            ->orderBy('p.postedAt', 'DESC')
            ->getQuery()->getArrayResult();

        return $posts;
    }


    public function getUserPostsWithLimitAndOffset($userId, $limit, $offset)
    {
        /** @var array */
        $posts = $this->createQueryBuilder('p')
            ->join('p.author', 'a')
            ->where('a.id = :author_id')
            ->setParameter('author_id', $userId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('p.postedAt', 'DESC')
            ->getQuery()->getArrayResult();

        return $posts;
    }

    /**
     * @param $postId
     * @return null
     */
    public function getPostById($postId)
    {
        /** @var array */
        $posts = $this->createQueryBuilder('p')
            ->where('p.id = ' . $postId)
            ->getQuery()->getArrayResult();
        if (count($posts) == 1) {
            return $posts[0];
        }
        return null;
    }
}