<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:28
 */

namespace AppBundle\Service;


use AppBundle\Entity\Account;
use AppBundle\Entity\Picture;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\SecurityContext;

class UserApiService extends BaseService
{
    const SERVICE_NAME = 'api.user.service';

    /** @var  UserPasswordEncoder */
    private $securityPasswordEncoder;

    /** @var  TokenStorage */
    private $tokenStorage;

    /** @var  Session */
    private $session;

    public function registerHandle($data)
    {
        $errors = array();

        $email = isset($data['email']) ?
            $data['email'] : (null AND $errors[] = '"email" is required');

        $plainPassword = isset($data['password']) ?
            $data['password'] : (null AND $errors[] = '"password" is required');

        $firstName = isset($data['nume']) ?
            $data['nume'] : (null AND $errors[] = 'please put your first name');

        $lastName = isset($data['prenume']) ?
            $data['prenume'] : (null AND $errors[] = 'please put yout last name');

        /** @var UserRepository $userRepository */
        $userRepository = $this->getEntityManager()
            ->getRepository('AppBundle:User');

        // make sure we don't already have this user!
        if ($existingUser = $userRepository->findUserByEmail($email)) {
            $errors[] = 'A user with this email is already registered!';
        }

        $user = new User();
        $account = new Account();
        $profile = new Profile();

        $defaultProfilePicture = $this->getEntityManager()
            ->getRepository('AppBundle:Picture')
            ->findOneBy(
                array(
                    'type'=>Picture::DEFAULT_PICTURE_TYPE,
                    'was'=>Picture::PROFILE_PICTURE_LABEL
                )
            );

        $defaultCoverPicture = $this->getEntityManager()
            ->getRepository('AppBundle:Picture')
            ->findOneBy(
                array(
                    'id'=>2
                )
            );

        $profile->setFirstName($firstName)
            ->setLastName($lastName)
            ->setProfilePicture($defaultProfilePicture)
            ->setCoverPicture($defaultCoverPicture)
            ->setLastChange(new \DateTime("now"));

        $encodedPassword = $this->getSecurityPasswordEncoder()
            ->encodePassword($account, $plainPassword);
        $account->setEmail($email)->setUsername($email)->setPassword($encodedPassword);
        $user->setAccount($account);
        $user->setProfile($profile);

        // errors? Show them!
        if (count($errors) > 0) {
           throw new Exception(json_encode($errors));
        }

        //$this->loginUser($user->getAccount());

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function dummyUserSignUp($data)
    {
        if(!isset($data['username'])){
            throw new \Exception('No email was given');
        }

        if(!isset($data['password'])){
            throw new \Exception('No password was given');
        }

        $password = $data['password'];
        $email = $data['username'];

        /** @var UserRepository $userRepository */
        $userRepository = $this->getEntityManager()
            ->getRepository('AppBundle:User');

        $user = $userRepository->findUserByEmail($email);

        if(!$user){
            throw new \Exception('Bad Credentials1!');
        }
/*

        $encodedPassword = $this->getSecurityPasswordEncoder()
            ->encodePassword($user->getAccount(), $password);
        $tempAccount = new  Account();
        $tempAccount->setPassword($tempAccount);*/


        if($this->getSecurityPasswordEncoder()->isPasswordValid($user->getAccount(), $password) ){
            throw new \Exception('Bad Credentials2!');
        }

        $this->loginUser($user->getAccount());
    }

    /**
     * @param Account $userAccount
     */
    public function loginUser(Account $userAccount)
    {
        $this->getSession()->start();
        $token = new UsernamePasswordToken($userAccount, $userAccount->getPassword(), 'main', $userAccount->getRoles());
        $this->getTokenStorage()->setToken($token);
        $this->getSession()->set('_security.last_username', $userAccount->getEmail());
        $this->getSession()->set('_security_secured_area', serialize($token));

        $this->getSession()->save();

        /*$cookie = new Cookie($this->getSession()->getName(), $this->getSession()->getId());
        $this->get->getCookieJar()->set($cookie);*/
    }

    /**
     * @return UserPasswordEncoder
     */
    public function getSecurityPasswordEncoder()
    {
        return $this->securityPasswordEncoder;
    }

    /**
     * @param UserPasswordEncoder $securityPasswordEncoder
     * @return UserApiService
     */
    public function setSecurityPasswordEncoder($securityPasswordEncoder)
    {
        $this->securityPasswordEncoder = $securityPasswordEncoder;

        return $this;
    }

    /**
     * @return TokenStorage
     */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }

    /**
     * @param TokenStorage $tokenStorage
     * @return UserApiService
     */
    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        return $this;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     * @return UserApiService
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }
}