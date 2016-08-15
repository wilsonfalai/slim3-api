<?php

namespace App\Domain\Users;

class UserResetEntity
{
    /**
     * Id of the user reset (UUID)
     *
     * @var string
     */
    protected $id;

    /**
     * Id of the user (UUID)
     *
     * @var string
     */
    protected $userId;

    /**
     * Token used to reset user
     *
     * @var string
     */
    protected $token;

    /**
     * Date/time the user reset was created
     *
     * @var string
     */
    protected $created;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id      = $data['id'];
            $this->userId  = $data['userId'];
            $this->token   = $data['token'];
            $this->created = $data['created'];
        }
    }

    /**
     * Gets the id of the user reset (UUID).
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the user reset (UUID).
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
     * Gets the id of the user (UUID).
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the id of the user (UUID).
     *
     * @param string $userId the user id
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the Token used to reset user.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the Token used to reset user.
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
     * Gets the Date/time the user reset was created.
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the Date/time the user reset was created.
     *
     * @param string $created the created
     *
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }
}
