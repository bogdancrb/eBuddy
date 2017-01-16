<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 13-Jan-17
 * Time: 14:28
 */

namespace AppBundle\Service;


use AppBundle\Entity\Profile;

class RecommenderEngineApiService extends BaseService
{
    const SERVICE_NAME = 'recc.api.service';

    /**
     * @return \AppBundle\Entity\Profile[]|array
     */
    public function getAllFriends(){
        $allUsers = $this->getEntityManager()->getRepository('AppBundle:Profile')->findAll();

        return $this->filterUsers($allUsers);
    }

    /**
     * @param Profile[] $users
     * @return array | Profile[]
     */
    private function filterUsers($users){
        $result = array();
        $pictureRepo = $this->getEntityManager()->getRepository('AppBundle:Picture');
        foreach ($users as $user){
            $result[] = $this->composeUserReturn($user);
        }

        return$result;
    }

    /**
     * @param Profile $user
     * @return array | Profile
     */
    private function composeUserReturn($user){
        if(!is_null($user)){
            $result = array(
                'id'=>$user->getUser()->getId(),
                'user_id'=>$user->getUser()->getId(),
                'name'=> $user->getFirstName().' '.$user->getLastName(),
                'picture'=> $user->getProfilePicture()->getPath()
            );
            return$result;
        }else {
            return $user;
        }

    }
}