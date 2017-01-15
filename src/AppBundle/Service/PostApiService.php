<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:59
 */

namespace AppBundle\Service;


use AppBundle\Entity\Appreciation;
use AppBundle\Entity\Post;

class PostApiService extends BaseService
{
    const SERVICE_NAME = 'api.post.service';


    public function updatePostAppreciation($data)
    {
        if (!isset($data['post_id'])) {
            throw new \Exception('A post id is needed');
        }

        if (!isset($data['status'])) {
            throw new \Exception('No status');
        }

        $post = $this->getEntityManager()
            ->getRepository('AppBundle:Post')
            ->findOneBy(
                array(
                    'id' => $data['post_id']
                )
            );

        if(is_null($post)){
            throw new \Exception('No such post');
        }

        $appreciation = $this->getEntityManager()
            ->getRepository('AppBundle:Appreciation')
            ->findOneBy(
                array(
                    'user' => $this->getLoggedUser(),
                    'post' => $post
                )
            );

        if(is_null($appreciation)){
            $appreciation= new Appreciation();
            $appreciation->setUser($this->getLoggedUser())
                ->setPost($post);
        }
        $appreciation->setStatus($data['status']);

        $this->getEntityManager()->persist($appreciation);
        $this->getEntityManager()->flush();
    }


    public function addNewPost($data)
    {
        if (!isset($data['post_content'])) {
            throw new \Exception('A post need to have a content');
        }

        $post = new Post();
        $post->setAuthor($this->getLoggedUser())
            ->setContent($data['post_content'])
            ->setPostedAt(new \DateTime("now"));

        $this->getEntityManager()->getRepository('AppBundle:Post')->savePost($post);
    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function getAllUserPostsWithLimitAndOffset($data)
    {

        if (!isset($data['limit'])) {
            throw new \Exception('Put limit in the payload');
        }
        if (!isset($data['offset'])) {
            throw new \Exception('Put offset in the payload');
        }

        $limit = $data['limit'];
        $offset = $data['offset'];

        $posts = $this->getEntityManager()->getRepository('AppBundle:Post')
            ->getUserPostsWithLimitAndOffset(
                $this->getLoggedUser()->getId(),
                $limit,
                $offset
            );

        $postsToBeSent = array();
        foreach ($posts as $post){
            $postsToBeSent[] = $this->preparePost($post);
        }

        return $postsToBeSent;
    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function getPostById($data)
    {

        if (!isset($data['post_id'])) {
            throw new \Exception('No postId');
        }

        /** @var Post $posts */
        $post = $this->getEntityManager()->getRepository('AppBundle:Post')
            ->getPostById($data['post_id']);

        return json_encode($post);
    }

    /**
     * @param Post $post
     * @return array|Post
     */
    public function preparePost($post)
    {
        if (!is_null($post)) {
            $result = array();

            $appreciation = $this->getEntityManager()
                ->getRepository('AppBundle:Appreciation')
                ->findOneBy(
                    array(
                        'user' => $this->getLoggedUser(),
                        'post' => $post
                    )
                );


            $appreciationStatus = is_null($appreciation)? 'null' : $appreciation->getStatus();


            $result['id'] = $post->getId();
            $result['content'] = $post->getContent();
            $result['appreciation_status'] = $appreciationStatus;
            $result['posted_at'] = $post->getPostedAt();
            $result['author_name'] = $post->getAuthor()->getProfile()->getFirstName() .
                ' ' .
                $post->getAuthor()->getProfile()->getLastName();
            $result['author_picure_path'] = $post->getAuthor()->getProfile()->getProfilePicture()->getPath();

            return $result;
        } else {
            return $post;
        }
    }
}