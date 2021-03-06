<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:25
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class BaseService
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Container */
    protected $container;

    protected $securityTokenStorage;

    /**
     * Contains all the logic needed in order to do an api request.
     *
     * @param string $function The number of the action.
     * @param mixed  $data     Needed parameters.
     * @return array
     */
    public function doRequest($function, $data = null)
    {
        error_reporting(E_ALL ^ E_STRICT);

        $result = array(
            'error' => false,
            'message' => '',
            'response' => false,
        );

        try {
            $method = str_replace('Action', '', $function);
            $result['response'] = call_user_func_array(get_class($this) . '::' . $method, array($data));
        } catch (\Exception $ex) {
            $result['error'] = true;
            $result['message'] = $ex->getMessage();
        }

        return json_encode($result);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return BaseService
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getLoggedUser()
    {
        $securityContext = $this->getSecurityTokenStorage();

        $token = $securityContext->getToken();
        $user = $token->getUser();

        return $this->getLoggedUserFromRepo($user->getId());
    }


    public function getLoggedUserFromRepo($accountId)
    {
        $user = $this->getEntityManager()
            ->getRepository('AppBundle:User')
            ->findOneBy(
                array(
                    'account'=>$accountId
                )
            );

        return $user;
    }

    /**
     * @return mixed
     */
    public function getSecurityTokenStorage()
    {
        return $this->securityTokenStorage;
    }

    /**
     * @param mixed $securityTokenStorage
     * @return BaseService
     */
    public function setSecurityTokenStorage($securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        return $this;
    }

}