<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 06-Nov-16
 * Time: 13:02
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class BaseEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BaseEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}