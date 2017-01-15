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
    public function getPicturesOfCurrentUser($data)
    {
        $pictures = $this->getEntityManager()->getRepository('AppBundle:Picture')
            ->findBy(
                array(
                    'author'=>$this->getLoggedUser()->getAccount()->getId()
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
            $result['path'] = $picture->getPath();
            $result['posted_at'] = $picture->getPostedAt();

            return $result;
        } else {
            return $picture;
        }
    }
}