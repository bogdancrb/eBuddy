<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Jan-17
 * Time: 10:21
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return \AppBundle\Entity\User
     */
    public function getLoggedUser()
    {
        $securityContext = $this->container->get('security.token_storage');

        $token = $securityContext->getToken();
        $user = $token->getUser();


        return $this->getLoggedUserFromRepo($user->getId());
    }

    public function getLoggedUserFromRepo($accountId)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:User')
            ->findOneBy(
                array(
                    'account'=>$accountId
                )
            );

//        $profile = $this->getDoctrine()->getManager()
//            ->getRepository('AppBundle:Profile')
//            ->findOneBy(
//                array(
//                    'id'=>($user->getProfile()->getId())
//                )
//            );
//
//        $user->setProfile($profile);

       // var_dump($profile->getCoverPicture()->getPath());
        return $user;
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