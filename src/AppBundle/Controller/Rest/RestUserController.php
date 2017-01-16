<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 14-Dec-16
 * Time: 19:30
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Account;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\UserApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RestUserController extends FOSRestController
{
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/user_register_handle.{format}", name="user_register_handle")
     * @return Response
     */
    public function registerHandleAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        /** @var UserApiService $userApiService */
        $userApiService = $this->get(UserApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);
        $result = $userApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }


    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/user_sign_up", name="user_sign_up")
     * @return Response
     */
    public function dummyUserSignUpAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        /** @var UserApiService $userApiService */
        $userApiService = $this->get(UserApiService::SERVICE_NAME);

        $data = json_decode($request->getContent(), true);
        $result = $userApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }

}