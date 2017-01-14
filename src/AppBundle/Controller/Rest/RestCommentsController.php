<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 20:51
 */

namespace AppBundle\Controller\Rest;

use AppBundle\Service\CommentsApiService;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class RestCommentsController
 * @package AppBundle\Controller\Rest
 */
class RestCommentsController extends BaseRestController
{
    /**
     * @param Request $request
     * @param int $postId
     *
     * @Rest\View
     * @Rest\Route("/api/v1/add_new_comment/{postId}", options={"expose"=true}, name="add_new_comment")
     *
     * @return string
     */
    public function addNewPostAction(Request $request, $postId)
    {
        /** @var CommentsApiService $commentsApiService */
        $commentsApiService = $this->get(CommentsApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $data['post_id'] = $postId;

        $result = $commentsApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

    /**
     * @param Request $request
     * @param int $postId
     * @param int $limit
     * @param int $offset
     *
     * @Rest\View
     * @Rest\Route("/api/v1/get_post_comments_with_limit_and_offset/{postId}/{limit}/{offset}", options={"expose"=true}, name="get_post_comments_with_limit_and_offset")
     *
     * @return string
     */
    public function getAllPostCommentsWithLimitAndOffsetAction(Request $request, $postId, $limit, $offset)
    {
        $req = array(
            'post_id' => $postId,
            'limit' => $limit,
            'offset' => $offset
        );

        /** @var CommentsApiService $commentsApiService */
        $commentsApiService = $this->get(CommentsApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $data['post_id'] = $postId;

        $result = $commentsApiService->doRequest(__FUNCTION__, $req);

        return new Response($result);

    }

    /**
     * @param Request $request
     * @param int $postId
     *
     * @Rest\View
     * @Rest\Route("/api/v1/get_last_comment_from_a_post/{postId}", options={"expose"=true}, name="get_last_comment_from_a_post")
     *
     * @return Response
     */
    public function getLastCommentFromAPostAction(Request $request, $postId)
    {
        /** @var CommentsApiService $commentsApiService */
        $commentsApiService = $this->get(CommentsApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $data['post_id'] = $postId;

        $result = $commentsApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }
}