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
        return $this->getDoctrine()->getRepository('AppBundle:User')->findUserByEmail('test@data.com');
    }
}