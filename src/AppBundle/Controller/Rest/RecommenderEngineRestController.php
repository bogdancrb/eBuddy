<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 13-Jan-17
 * Time: 14:43
 */

namespace AppBundle\Controller\Rest;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Service\RecommenderEngineApiService;

class RecommenderEngineRestController extends BaseRestController
{

    /**
     * @param Request $request
     *
     * @Rest\View
     * @Rest\Route("/api/v1/get_all_users", options={"expose"=true}, name="get_recomanded_friends")
     *
     * @return string
     */
    public function getAllFriendsAction(Request $request)
    {
        /** @var RecommenderEngineApiService $recommenderEngineApiService */
        $recommenderEngineApiService = $this->get(RecommenderEngineApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);

        $result = $recommenderEngineApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }
}