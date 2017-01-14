<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/profile_edit", name="profile_edit")
     */
    public function indexAction(Request $request)
    {
        return $this->render('profile_edit_page.html.twig', array(
            'user' => $this->getLoggedUser()
        ));
        
//        if(!$this->isUserLoggedIn()){
//            return $this->render('profile_edit_page.html.twig', array(
//                'user' => $this->getLoggedUser()
//            ));
//        }
//
//        return $this->render('default/index.html.twig', array(
//            'user' => $this->getLoggedUser()
//        ));
    }

    /**
     * @Route("/", name="homepage")
     */
    public function welcomeAction(Request $request)
    {
        if($this->isUserLoggedIn()){
            return $this->render('welcome.html.twig' );
        }

        return $this->render('first_page.html.twig');
    }
    /**
     * Is the current user logged in?
     *
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return $this->container->get('security.authorization_checker')
            ->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getLoggedUser()
    {
        return $this->getDoctrine()->getRepository('AppBundle:User')->findUserByEmail('test@data.com');
    }
}
