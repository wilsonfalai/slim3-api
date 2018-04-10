<?php

namespace App\Domain\Users;

use Respect\Validation\Validator as v;

class UserEntity
{
    /**
     * Id of the user (UUID)
     *
     * @var string
     */
    protected $id;

    /**
     * Name of the user
     *
     * @var string
     */
    protected $name;

    /**
     * Email belonging to the user
     *
     * @var string
     */
    protected $email;

    /**
     * Password set by the user
     *
     * @var string
     */
    protected $password;

    /**
     * Token used to activate user
     *
     * @var string
     */
    protected $token;

    /**
     * Status of the user activation
     *
     * @var bool
     */
    protected $status;

    /**
     * Date/time the user was created
     *
     * @var string
     */
    protected $created_at;

    /**
     * Date/time the user was updated
     *
     * @var string
     */
    protected $updated_at;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->token = $data['token'];
            $this->status = $data['status'];
            $this->created_at = $data['created_at'];
            $this->updated_at = $data['updated_at'];
        }
    }


    /**
     * Gets the Id of the user (UUID).
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the Id of the user (UUID).
     *
     * @param string $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the Name of the user.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name of the user.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the Email belonging to the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the Email belonging to the user.
     *
     * @param string $email the email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the Password set by the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the Password set by the user.
     *
     * @param string $password the password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets the Token used to activate user.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the Token used to activate user.
     *
     * @param string $token the token
     *
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Gets the Status of the user activation.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the Status of the user activation.
     *
     * @param bool $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the Date/time the user was created.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Sets the Date/time the user was created.
     *
     * @param string $created the created
     *
     * @return self
     */
    public function setCreated($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Gets the Date/time the user was created.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Sets the Date/time the user was created.
     *
     * @param string $created the created
     *
     * @return self
     */
    public function setUpdated($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }
}
