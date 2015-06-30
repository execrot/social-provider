<?php

namespace Social\Model; 

/**
 * Class User
 * @package Social\Model
 */
class User extends AbstractModel
{
    /**
     * @var int
     */
    protected $_id = null;

    /**
     * @var string
     */
    protected $_name = null;

    /**
     * @var string
     */
    protected $_email = null;

    /**
     * @var string
     */
    protected $_gender = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }
}
