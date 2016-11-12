<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 15:42
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"picture" = "Picture", "profile_picture" = "ProfilePicture",  "cover_picture" = "CoverPicture"})
 */
class Picture extends BaseEntity
{
    /** @const */
    const PROFILE_PICTURE_LABEL = "profile_picture";
    /** @const */
    const COVER_PICTURE_LABEL = "cover_picture";
    /** @const */
    const REGULAR_PICTURE_LABEL = "regular_picture";

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $path;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('profile_picture', 'cover_picture','regular_picture')", nullable=false, options={"unsigned":true, "default":"regular_picture"})
     */
    private $was;
}