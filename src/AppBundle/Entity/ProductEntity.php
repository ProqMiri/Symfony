<?php
/**
 * Created by PhpStorm.
 * User: Mirnamiq
 * Date: 10/17/2017
 * Time: 11:05 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tb_products")
 */
class ProductEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $is_deleted;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * @param mixed $is_deleted
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }

    public function __construct()
    {
        $this->is_deleted = 0;
    }

}