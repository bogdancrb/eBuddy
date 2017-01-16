<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:41
 */

namespace AppBundle\Service;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;

class CommentsApiService extends BaseService
{
    const SERVICE_NAME = 'api.comment.service';

    /**
     * @param $data
     * @throws \Exception
     */
    public function addNewPost($data)
    {
        if (!isset($data['comment_content'])) {
            throw new \Exception('A post need to have a content');
        }

        if (!isset($data['post_id'])) {
            throw new \Exception('No postId');
        }

        $postId = $data['post_id'];

        /** @var Post $post */
        $post = $this->getEntityManager()
            ->getRepository('AppBundle:Post')
            ->find($postId);

        $comment = new Comment();
        $comment->setUser($this->getLoggedUser())
            ->setContent($data['comment_content'])
            ->setPostedAt(new \DateTime("now"))
            ->setPost($post);

        $this->getEntityManager()
            ->getRepository('AppBundle:Comment')
            ->saveComment($comment);
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function getAllPostCommentsWithLimitAndOffset($data)
    {
        if (!isset($data['post_id'])) {
            throw new \Exception('No postId');
        }

        if (!isset($data['limit'])) {
            throw new \Exception('No limit');
        }

        if (!isset($data['offset'])) {
            throw new \Exception('No offset');
        }

        $comments = $this->getEntityManager()->getRepository('AppBundle:Comment')
            ->getAllPostCommentsWithLimitAndOffset(
                $data['post_id'],
                $data['limit'],
                $data['offset']
            );

        $commentsToBeSent = array();

        foreach ($comments as $comment){
            $commentsToBeSent[] = $this->prepareComment($comment);
        }

        return json_encode($commentsToBeSent);

    }

    /**
     * @param array $data
     * @return Comment
     * @throws \Exception
     */
    public function getLastCommentFromAPost($data)
    {

        if (!isset($data['post_id'])) {
            throw new \Exception('No postId');
        }

        /** @var Comment $comment */
        $comment = $this->getEntityManager()->getRepository('AppBundle:Comment')
            ->getLastCommentOfAPost(intval($data['post_id']));

        if ($comment) {
            $author = $this->getEntityManager()->getRepository('AppBundle:User')
                ->find($comment->getUser()->getId());

            $comment->setUser($author);
        }

        return $this->prepareComment($comment);
    }

    /**
     * @param Comment $comment
     * @return array|Comment
     */
    public function prepareComment($comment)
    {
        if (!is_null($comment)) {
            $result = array();
            $result['content'] = $comment->getContent();
            $result['posted_at'] = $comment->getPostedAt();
            $result['author_id'] = $comment->getUser()->getId();
            $result['author_name'] = $comment->getUser()->getProfile()->getFirstName() .
                ' ' .
                $comment->getUser()->getProfile()->getLastName();
            $result['author_picure_path'] = $comment->getUser()->getProfile()->getProfilePicture()->getPath();

            return $result;
        } else {
            return $comment;
        }
    }
}