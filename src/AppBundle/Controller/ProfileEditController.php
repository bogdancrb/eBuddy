<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 15-Dec-16
 * Time: 12:38
 */

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProfileEditController extends Controller
{
    /**
     * @Route("/profile_edit", name="profile_edit")
     */
    public function indexAction(Request $request)
    {
        return $this->render('profile_edit_page.html.twig');
    }

    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/user_register_handle.{format}", name="user_register_handle")
     * @return Response
     */
    public function getProfileDetails(Request $request)
    {
        $errors = array();

        $data = json_decode($request->getContent(), true);

        $response = array(
''
        );

        return new Response($data);
    }
}