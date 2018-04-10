<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 15:20
 */

namespace App\Domain\Client;

use PDO;
use App\Domain\Client\ClientEntity as Client;


class ClientRepository implements ClientInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getClient($id)
    {
        $sql = "SELECT `id`, `first_name`, `last_name`, `email`, `birth_date`, `created_at`, `updated_at`, `password`, `status`, `phone`, `document_number` , `token`
            FROM `client`
            WHERE `client`.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch();

        if ($client) {
            return new Client($client);
        }
        return false;
    }

    public function getClients($page = 0, $perPage = 15)
    {
        $sql = "SELECT `id`, `first_name`, `last_name`, `email`, `birth_date`, `created_at`, `updated_at`, `password`, `status`, `phone`, `document_number` , `token`
            FROM `ecommerce`.`client`
            WHERE `client`.`status` = 1
            LIMIT $page, $perPage";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new Client($row);
        }
        return $results;
    }

    public function saveClient(Client $client)
    {
        $sql = "INSERT INTO `client`
            (id, first_name, last_name, email, birth_date, password, status, phone, document_number, created_at, updated_at, token) VALUES
            (:id, :first_name, :last_name, :email, :birth_date, :password, :status, :phone, :document_number, :created_at, :updated_at, :token)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $client->getId(),
            'first_name'     => $client->getFirstName(),
            'last_name'    => $client->getLastName(),
            'email' => $client->getEmail(),
            'birth_date'    => $client->getBirthDate(),
            'password'   => $client->getPassword(),
            'token' => $client->getToken(),
            'status'  => $client->getStatus(),
            'phone'  => $client->getPhone(),
            'document_number'  => $client->getDocumentNumber(),
            'created_at'  => $client->getCreatedAt(),
            'updated_at'  => $client->getUpdatedAt()
        ]);

        if ($result) {
            return true;
        }
        return false;
    }

    public function getClientByEmail($email)
    {
        $sql = "SELECT `id`, `first_name`, `last_name`, `email`, `birth_date`, `created_at`, `updated_at`, `password`, `status`, `phone`, `document_number`
            FROM `client`
            WHERE `client`.`email` = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $client = $stmt->fetch();

        if ($client) {
            return new Client($client);
        }
        return false;
    }
}