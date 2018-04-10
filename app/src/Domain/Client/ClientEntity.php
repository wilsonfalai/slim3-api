<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 14:19
 */

namespace App\Domain\Client;

use Respect\Validation\Validator as v;

class ClientEntity
{
    /**
     * @var string Identificador único usando uuid
     */
    protected $id;
    /**
     * @var string
     */
    protected $first_name;
    /**
     * @var string
     */
    protected $last_name;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $birth_date;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var int
     */
    protected $status;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var string
     */
    protected $document_number;

    /**
     * @var string
     */
    protected $token;

    /**
     * ClientEntity constructor.
     * @param string $id
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $birth_date
     * @param string $created_at
     * @param string $updated_at
     * @param string $password
     * @param int $status
     * @param string $phone
     * @param string $document_number
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'];
            $this->first_name = $data['first_name'];
            $this->last_name = $data['last_name'];
            $this->email = $data['email'];
            $this->birth_date = $data['birth_date'];
            $this->created_at = $data['created_at'];
            $this->updated_at = $data['updated_at'];
            $this->password = $data['password'];
            $this->status = $data['status'];
            $this->phone = $data['phone'];
            $this->document_number = $data['document_number'];
        }
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * @param string $birth_date
     */
    public function setBirthDate($birth_date)
    {
        $this->birth_date = $birth_date;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    /**
     * @param string $document_number
     */
    public function setDocumentNumber($document_number)
    {
        $this->document_number = $document_number;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }




}