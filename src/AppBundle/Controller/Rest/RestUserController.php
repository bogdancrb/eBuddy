<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 14-Dec-16
 * Time: 19:30
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Account;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RestUserController extends FOSRestController
{
    /**
     * @param Request $request
     * @Rest\View
     * @Rest\Route("/api/v1/user_register_handle.{format}", name="user_register_handle")
     * @return Response
     */
    public function registerHandleAction(Request $request)
    {
        $errors = array();

        $data = json_decode($request->getContent(), true);

        $email = isset($data['email']) ?
            $data['email'] : (null AND  $errors[] = '"email" is required');

        $plainPassword = isset($data['plain_password']) ?
            $data['plain_password'] : (null AND $errors[] = '"password" is required');

        $firstName = isset($data['first_name']) ?
            $data['first_name'] : (null AND $errors[] = 'please put your first name');

        $lastName = isset($data['last_name']) ?
            $data['last_name'] : (null AND $errors[] = 'please put yout last name');

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()
            ->getRepository('AppBundle:User');

        // make sure we don't already have this user!
        if ($existingUser = $userRepository->findUserByEmail($email)) {
            $errors[] = 'A user with this email is already registered!';
        }

        $user = new User();
        $account = new Account();
        $profile = new Profile();

        $profile->setFirstName($firstName)->setLastName($lastName)->setLastChange(new \DateTime("now"));

        $encodedPassword = $this->container->get('security.password_encoder')
            ->encodePassword($account, $plainPassword);
        $account->setEmail($email)->setPassword($encodedPassword);

        $user->setAccount($account);
        $user->setProfile($profile);

        // errors? Show them!
        if (count($errors) > 0) {
            return new Response(json_encode($errors));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->loginUser($user->getAccount());

        return new Response('ok');
    }

    /**
     * Logs this user into the system
     *
     * @param User $user
     */
    public function loginUser(Account $userAccount)
    {
        $token = new UsernamePasswordToken($userAccount, $userAccount->getPassword(), 'main', $userAccount->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }
}