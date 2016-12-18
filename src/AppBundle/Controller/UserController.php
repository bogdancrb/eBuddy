<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends Controller
{

    /**
     * @Route("/register", name="user_register")
     * @Method("GET")
     */
    public function registerAction()
    {
        if ($this->isUserLoggedIn()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('user/register.twig', array('user' => new Account()));
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        throw new \Exception('Should not get here - this should be handled magically by the security system!');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Should not get here - this should be handled magically by the security system!');
    }

    /**
     * Logs this user into the system
     *
     * @param User $user
     */
    public function loginUser(User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getAccount()->getPassword(), 'main', $user->getAccount()->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }

    /**
     * @Route("/login", name="security_login_form")
     */
    public function loginAction(Request $request)
    {
        if ($this->isUserLoggedIn()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('user/login.twig', array(
            'error'         => $this->container->get('security.authentication_utils')->getLastAuthenticationError(),
            'last_username' => $this->container->get('security.authentication_utils')->getLastUserName()
        ));
    }

    public function addFlash($message, $positiveNotice = true)
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $noticeKey = $positiveNotice ? 'notice_happy' : 'notice_sad';

        $request->getSession()->getFlashbag()->add($noticeKey, $message);
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
