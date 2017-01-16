<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 16-Dec-16
 * Time: 12:45
 */

namespace AppBundle\Controller\Rest;


use FOS\RestBundle\Controller\FOSRestController;

class BaseRestController extends FOSRestController
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

        return $user;
    }
}