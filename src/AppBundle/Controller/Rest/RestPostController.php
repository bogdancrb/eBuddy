<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 12:45
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Post;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;

class RestPostController extends BaseRestController
{
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/add_new_post", name="add_new_post")
     * @return string
     */
    public function addNewPostAction(Request $request)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        $requestData = json_decode($request->getContent(), true);

        try {

            if (!isset($requestData['post_content'])) {
                throw new Exception('A post need to have a content');
            }

            $post = new Post();
            $post->setAuthor($this->getLoggedUser())
                ->setContent($requestData['post_content'])
                ->setPostedAt(new \DateTime("now"));

            $this->getDoctrine()->getRepository('AppBundle:Post')->savePost($post);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_user_posts_with_limit_and_offset", name="get_user_posts_with_limit_and_offset")
     * @return string
     */
    public function getAllUserPostsWithLimitAndOffsetAction(Request $request)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        $requestData = json_decode($request->getContent(), true);

        try {

            if (!isset($requestData['limit'])) {
                throw new Exception('Put limit in the payload');
            }
            if (!isset($requestData['offset'])) {
                throw new Exception('Put offset in the payload');
            }

            $limit = $requestData['limit'];
            $offset = $requestData['offset'];

            $posts = $this->getDoctrine()->getRepository('AppBundle:Post')
                ->getUserPostsWithLimitAndOffset(
                    $this->getLoggedUser()->getId(),
                    $limit,
                    $offset
                );

            $response['response'] = json_encode($posts);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }


    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_post_by_id/{postId}", options={"expose"=true}, name="get_post_by_id")
     * @return string
     */
    public function getPostByIdAction(Request $request, $postId)
    {
        $response = array(
            'error' => false,
            'message' => '',
            'response' => true
        );

        try {
            /** @var Post $posts */
            $post = $this->getDoctrine()->getRepository('AppBundle:Post')
                ->getPostById($postId);

            $response['response'] = json_encode($post);

        } catch (\Exception $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();
        }

        return new Response(json_encode($response));
    }

}