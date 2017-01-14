<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Service\FriendApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class RestAddFriendController extends BaseRestController
{
	/**
	 * @param Request $request
	 * @Rest\View
	 * @Rest\Route("/api/v1/send_friend_request", name="send_friend_request")
	 * @return Response
	 */
	public function sendFriendRequestAction(Request $request)
	{
		/** @var FriendApiService $postApiService */
		$friendApiService = $this->get(FriendApiService::SERVICE_NAME);

//		$data = json_decode($request->getContent(), true);
		$data = [];

		$result = $friendApiService->doRequest(__FUNCTION__, $data);

		return new Response($result);
	}
}