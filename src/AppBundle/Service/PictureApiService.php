<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:41
 */

namespace AppBundle\Service;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Picture;
use AppBundle\Entity\Post;

class PictureApiService extends BaseService
{
    const SERVICE_NAME = 'api.picture.service';


    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function changeProfilePicture($data){
        if(!isset($data['picture_id']) || is_null($data['picture_id'])){
            throw new \Exception('No pictureId');
        }
        /** @var Picture $picture */
        $picture = $this->getEntityManager()->getRepository('AppBundle:Picture')
            ->findOneBy(
                array('id'=>$data['picture_id'])
            );
        $picture->setWas(Picture::PROFILE_PICTURE_LABEL);

        $this->getLoggedUser()->getProfile()->setProfilePicture($picture);

        $this->getEntityManager()->persist(
            $this->getLoggedUser()->getProfile()
        );
        $this->getEntityManager()->flush();
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function changeCoverPicture($data){
        if(!isset($data['picture_id']) || is_null($data['picture_id'])){
            throw new \Exception('No pictureId');
        }
        /** @var Picture $picture */
        $picture = $this->getEntityManager()->getRepository('AppBundle:Picture')
            ->findOneBy(
                array('id'=>$data['picture_id'])
            );
        $picture->setWas(Picture::COVER_PICTURE_LABEL);

        $this->getLoggedUser()->getProfile()->setCoverPicture($picture);

        $this->getEntityManager()->persist(
            $this->getLoggedUser()->getProfile()
        );
        $this->getEntityManager()->flush();
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function getPicturesOfCurrentUser($data)
    {
        $pictures = $this->getEntityManager()->getRepository('AppBundle:Picture')
            ->findBy(
                array(
                    'author'=>$this->getLoggedUser()->getProfile()->getId()
                )
            );

        $result = array();
        foreach ($pictures as $picture){
            $result[] = $this->serializePicture($picture);
        }

        return $result;

    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function getPicturesOfUser($data)
    {
        $pictures = array();
        if( isset($data['user_id']) || is_null($data['user_id'])) {
            $pictures = $this->getEntityManager()->getRepository('AppBundle:Picture')
                ->findBy(
                    array(
                        'author' => $data['user_id']
                    )
                );
        }

        $result = array();
        foreach ($pictures as $picture){
            $result[] = $this->serializePicture($picture);
        }

        return $result;
    }

    /**
     * @param Picture $picture
     * @return array|Picture
     */
    public function serializePicture($picture)
    {
        if (!is_null($picture)) {
            $result = array();
            $result['id'] = $picture->getId();
            $result['path'] = $picture->getPath();
            $result['posted_at'] = $picture->getPostedAt();

            return $result;
        } else {
            return $picture;
        }
    }
}