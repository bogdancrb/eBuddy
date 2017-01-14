<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 14-Jan-17
 * Time: 20:51
 */

namespace AppBundle\Controller\Rest;

use AppBundle\Service\ProfileApiService;
use AppBundle\Service\UserApiService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RestProfileController extends FOSRestController
{

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/profile_update", options={"expose"=true}, name="profile_update")
     * @return Response
     */
    public function profileUpdateAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && !$request->isMethod('POST')) {
            throw new HttpException('XMLHttpRequests/AJAX calls must be POSTed');
        }

        $data['other_data'] = $request->request->get('other_data');
        $data['profile_picture'] = $profilePicture = $request->files->get('profile_picture');
        $data['cover_picture'] = $coverPicture = $request->files->get('cover_picture');

        /** @var ProfileApiService $profileApiService */
        $profileApiService = $this->get(ProfileApiService::SERVICE_NAME);


        $result = $profileApiService->doRequest(__FUNCTION__, $data);

        return new Response($result);
    }


}