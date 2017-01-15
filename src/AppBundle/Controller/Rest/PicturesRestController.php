<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 15-Jan-17
 * Time: 10:44
 */

namespace AppBundle\Controller\Rest;

use AppBundle\Service\PictureApiService;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class PicturesRestController extends BaseRestController
{
    /**
     * @param Request $request
     *
     * @Rest\View
     * @Rest\Route("/api/v1/get_all_pictures_of_logged_user", options={"expose"=true}, name="getPictursOfCurrentUser")
     *
     * @return string
     */
    public function getPicturesOfCurrentUserAction(Request $request)
    {
        /** @var PictureApiService $commentsApiService */
        $commentsApiService = $this->get(PictureApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $result = $commentsApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

    /**
     * @param Request $request
     *
     * @Rest\View
     * @Rest\Route("/api/v1/get_all_pictures_of_user/{user_id}", options={"expose"=true}, name="getPictursOfUser")
     *
     * @return string
     */
    public function getPicturesOfUserAction(Request $request, $user_id)
    {
        /** @var PictureApiService $commentsApiService */
        $commentsApiService = $this->get(PictureApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);
        $data['user_id']= $user_id;

        $result = $commentsApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }
}