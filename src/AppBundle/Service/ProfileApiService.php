<?php
/**
 * Created by PhpStorm.
 * User: marius.iliescu
 * Date: 06-Jan-17
 * Time: 11:25
 */

namespace AppBundle\Service;


use AppBundle\Entity\Picture;
use AppBundle\Entity\Profile;
use AppBundle\Entity\ProfilePicture;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileApiService extends BaseService
{
    const SERVICE_NAME = 'api.profile.service';

    const PNG_FILE= 'png';

    /** @var  string */
    private $uploadDirPath;
    /** @var  string */
    private $pathInServer;

    /**
     * @param $data
     * @return array
     */
    public function profileUpdate($data){
        $profile = $this->getLoggedUser()->getProfile();
        $files = array();

        if(isset($data['profile_picture']) && !is_null($data['profile_picture'])) {

            /** @var UploadedFile $picture */
            $picture = $data['profile_picture'];
            $extension = $this->guessExtension($picture->getClientOriginalExtension());
            $hashedName = uniqid(rand(), true) .'.'. $extension;
            $files[] = $picture->move($this->getPathInServer().$this->getUploadDirPath(), $hashedName);

            $profilePicture = new Picture();
            $profilePicture->setType(Picture::CUSTOM_PICTURE_TYPE)
                ->setPath($this->getUploadDirPath().$hashedName)
                ->setWas(Picture::PROFILE_PICTURE_LABEL)
                ->setAuthor($this->getLoggedUser()->getAccount())
                ->setPostedAt(new \DateTime());
            $profile->setProfilePicture($profilePicture);

        }

        if(isset($data['cover_picture']) && !is_null($data['cover_picture'])) {

            /** @var UploadedFile $picture */
            $picture = $data['cover_picture'];
            $extension = $this->guessExtension($picture->getClientOriginalExtension());
            $hashedName = uniqid(rand(), true) .'.'. $extension;
            $files[] = $picture->move($this->getPathInServer().$this->getUploadDirPath(),$hashedName);

            $coverPicture = new Picture();
            $coverPicture->setType(Picture::CUSTOM_PICTURE_TYPE)
                ->setPath($this->getUploadDirPath().$hashedName)
                ->setWas(Picture::PROFILE_PICTURE_LABEL)
                ->setAuthor($this->getLoggedUser()->getAccount())
                ->setPostedAt(new \DateTime());
            $profile->setCoverPicture($coverPicture);

        }

        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();

        return $this->serializeNewUserData($profile);
    }

    public function guessExtension($filename){
        $extension = explode('.',$filename);
        count($extension) > 0 ? $extension=end($extension) : $extension = self::PNG_FILE;

        return $extension;
    }

    /**
     * @param Profile $profile
     * @return array | null
     */
    public function serializeNewUserData($profile){
        if(!is_null($profile)) {
            return array(
                'full_name' => $profile->getFirstName() . ' ' . $profile->getLastName(),
                'cover_image' => $profile->getCoverPicture()->getPath(),
                'profile_picture' => $profile->getProfilePicture()->getPath(),
                'address' => $profile->getAddress()
            );
        }

        return null;
    }
    /**
     * @return string
     */
    public function getUploadDirPath()
    {
        return $this->uploadDirPath;
    }

    /**
     * @param string $uploadDirPath
     * @return ProfileApiService
     */
    public function setUploadDirPath($uploadDirPath)
    {
        $this->uploadDirPath = $uploadDirPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathInServer()
    {
        return $this->pathInServer;
    }

    /**
     * @param string $pathInServer
     * @return ProfileApiService
     */
    public function setPathInServer($pathInServer)
    {
        $this->pathInServer = $pathInServer;
        return $this;
    }
}