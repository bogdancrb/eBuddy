<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 20:51
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Acl\Exception\Exception;

class RestCommentsController extends BaseRestController
{
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/add_new_comment/{postId}", options={"expose"=true}, name="add_new_comment")
     * @return string
     */
    public function addNewPostAction(Request $request, $postId)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        $requestData = json_decode($request->getContent(), true);

        try {

            if(!isset($requestData['comment_content'])){
                throw new \Exception('A post need to have a content');
            }

            /** @var Post $post */
            $post = $this->getDoctrine()
                ->getRepository('AppBundle:Post')
                ->find($postId);

            $comment = new Comment();
            $comment->setUser($this->getLoggedUser())
                ->setContent($requestData['comment_content'])
                ->setPostedAt(new \DateTime("now"))
                ->setPost($post);

            $this->getDoctrine()
                ->getRepository('AppBundle:Comment')
                ->saveComment($comment);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_post_comments_with_limit_and_offset/{postId}/{limit}/{offset}", options={"expose"=true}, name="get_post_comments_with_limit_and_offset")
     * @return string
     */
    public function getAllPostCommentsWithLimitAndOffsetAction(Request $request, $postId, $limit, $offset)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        try {

            $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')
                ->getAllPostCommentsWithLimitAndOffset(
                    $postId,
                    $limit,
                    $offset
                );

            $response['response'] = json_encode($comments);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_last_comment_from_a_post/{postId}", options={"expose"=true}, name="get_last_comment_from_a_post")
     * @return Response
     */
    public function getLastCommentFromAPost(Request $request, $postId)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        try {
            /** @var Comment $comment */
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')
                ->getLastCommentOfAPost(intval($postId));
            $response['response'] = json_encode($comment);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }
}