<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 15-Dec-16
 * Time: 12:38
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProfileEditController extends Controller
{

    /**
     * @Route("/profile", options={"expose"=true}, name="profile_edit")
     */
    public function profileEdit(Request $request)
    {

        $address = null;
        if($this->getLoggedUser()->getProfile()->getAddress()) {
            $address = $this->getDoctrine()
                ->getRepository('AppBundle:Address')
                ->findOneBy(array('id' =>
                        $this->getLoggedUser()->getProfile()->getAddress()->getId())
                );
        }

        return $this->render('profile_page.html.twig', array(
                'user' => $this->getLoggedUser(),
                'address' =>$address,
                'isGuest' => false,
                'other_user'=> false
            )
        );
    }


    /**
     * @Route("/profile/{userId}", name="profile_edit_user")
     */
    public function profileView(Request $request, $userId)
    {
        $address = null;
        if($this->getLoggedUser()->getProfile()->getAddress()) {
            $address = $this->getDoctrine()
                ->getRepository('AppBundle:Address')
                ->findOneBy(array('id' =>
                        $this->getLoggedUser()->getProfile()->getAddress()->getId())
                );
        }

        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(
                array(
                    'id'=> $userId
                )
            );

        if(!$this->isUserLoggedIn()){
            return $this->render('profile_page.html.twig', array(
                    'user' => $user,
                    'address' =>$address,
                    'isGuest' => true,
                    'other_user'=>$user->getId() != $this->getLoggedUser()->getId()
                )
            );
        }

        return $this->render('default/index.html.twig', array(
            'user' => $this->getLoggedUser()
        ));
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getLoggedUser()
    {
        return $this->getDoctrine()->getRepository('AppBundle:User')->findUserByEmail('test@data.com');
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
}