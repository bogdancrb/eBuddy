<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Service\FriendApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class RestFriendController extends BaseRestController
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

		$data = json_decode($request->getContent(), true);

		$result = $friendApiService->doRequest(__FUNCTION__, $data);

		return new Response($result);
	}

	/**
	 * @Rest\View
	 * @Rest\Route("/api/v1/get_friend_requests", name="get_friend_requests")
	 * @return Response
	 */
	public function getFriendRequestsAction()
	{
		/** @var FriendApiService $postApiService */
		$friendApiService = $this->get(FriendApiService::SERVICE_NAME);

		$result = $friendApiService->doRequest(__FUNCTION__);

		return new Response($result);
	}

	/**
	 * @param Request $request
	 * @Rest\View
	 * @Rest\Route("/api/v1/accept_friend_request", name="accept_friend_request")
	 * @return Response
	 */
	public function acceptFriendRequestAction(Request $request)
	{
		/** @var FriendApiService $postApiService */
		$friendApiService = $this->get(FriendApiService::SERVICE_NAME);

		$data = json_decode($request->getContent(), true);
		$result = $friendApiService->doRequest(__FUNCTION__, $data);

		return new Response($result);
	}
}