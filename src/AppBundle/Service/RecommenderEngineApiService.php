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
        $excludeFriendsIds = $this->getEntityManager()->getRepository('AppBundle:Relationship')->findFriendsByUserIdAndByStatus(
            $this->getLoggedUser()->getId()
        );

        $allUsers = $this->getEntityManager()->getRepository('AppBundle:Profile')->getAllProfiles(
            $this->getLoggedUser()->getProfile()->getId(),
            $excludeFriendsIds
        );

        return $this->filterUsers($allUsers);
    }

    /**
     * @param Profile[] $users
     * @return array | Profile[]
     */
    private function filterUsers($users){
        $result = array();
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
                'profile_id' => $user->getId()
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