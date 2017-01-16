<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 12:45
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Service\PostApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RestPostController
 * @package AppBundle\Controller\Rest
 */
class RestPostController extends BaseRestController
{
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/change_appreciation", options={"expose"=true}, name="changeAppreciation")
     * @return Response
     */
    public function updatePostAppreciationAction(Request $request){
        /** @var PostApiService $postApiService */
        $postApiService = $this->get(PostApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $result = $postApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/add_new_post", options={"expose"=true},  name="add_new_post")
     * @return Response
     */
    public function addNewPostAction(Request $request)
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');

        /** @var PostApiService $postApiService */
        $postApiService = $this->get(PostApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $result = $postApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_logged_user_posts_with_limit_and_offset",  options={"expose"=true}, name="get_user_posts_with_limit_and_offset")
     * @return Response
     */
    public function getAllUserPostsWithLimitAndOffsetAction(Request $request)
    {


        /** @var PostApiService $postApiService */
        $postApiService = $this->get(PostApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);
        $result = $postApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_c_user_posts_with_limit_and_offset",  options={"expose"=true}, name="get_c_user_posts_with_limit_and_offset")
     * @return Response
     */
    public function getUserPostsWithLimitAndOffsetAction(Request $request)
    {
        /** @var PostApiService $postApiService */
        $postApiService = $this->get(PostApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $result = $postApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }


    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/get_post_by_id/{postId}", options={"expose"=true}, name="get_post_by_id")
     * @return Response
     */
    public function getPostByIdAction(Request $request, $postId)
    {
        /** @var PostApiService $postApiService */
        $postApiService = $this->get(PostApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $data['post_id'] = $postId;

        $result = $postApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

}