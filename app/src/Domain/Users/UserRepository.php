<?php

namespace App\Domain\Users;

use App\Domain\Users\UserInterface;
use App\Domain\Users\UserEntity as User;
use App\Domain\Users\UserResetEntity as UserReset;
use PDO;

class UserRepository implements UserInterface
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

    public function getUsers($page = 0, $perPage = 15)
    {
        $sql = "SELECT `user`.`id`, `user`.`name`, `user`.`email`, `user`.`password`, `user`.`token`, `user`.`status`, `user`.`created_at`, `user`.`updated_at`
            FROM `user`
            WHERE `user`.`status` > 0
            LIMIT $page, $perPage";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new User($row);
        }
        return $results;
    }

    public function getUser($id)
    {
        $sql = "SELECT `user`.`id`, `user`.`name`, `user`.`email`, `user`.`password`, `user`.`token`, `user`.`status`, `user`.`created_at`, `user`.`updated_at`
            FROM `user`
            WHERE `user`.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if ($user) {
            return new User($user);
        }
        return false;
    }

    public function getUserByName($name)
    {

    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT `user`.`id`, `user`.`name`, `user`.`email`, `user`.`password`, `user`.`token`, `user`.`status`, `user`.`created_at`
            FROM `user`
            WHERE `user`.`email` = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            return new User($user);
        }
        return false;
    }

    public function getUserByUsername($username)
    {

    }

    public function getUserReset($userId, $token)
    {
        $sql = "SELECT `user_reset`.`id`, `user_reset`.`user_id`, `user_reset`.`token`, `user_reset`.`created_at`
            FROM `user_reset`
            WHERE `user_reset`.`user_id` = :user_id AND `user_reset`.`token` = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'token' => $token,
        ]);
        $userReset = $stmt->fetch();

        if ($userReset) {
            return new UserReset($userReset);
        }
        return false;
    }

    public function saveUser(User $user)
    {
        $sql = "INSERT INTO `user`
            (id, name, email, password, token, status, created_at, updated_at) VALUES
            (:id, :name, :email, :password, :token, :status, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $user->getId(),
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'password' => $user->getPassword(),
            'token'    => $user->getToken(),
            'status'   => $user->getStatus(),
            'created_at'  => $user->getCreatedAt(),
            'updated_at'  => $user->getUpdatedAt(),
        ]);
        
        if ($result) {
            return true;
        }
        return false;
    }

    public function saveUserReset(UserReset $userReset)
    {
        $sql = "INSERT INTO `user_reset`
            (id, user_id, token, created_at) VALUES
            (:id, :user_id, :token, :created_at)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $userReset->getId(),
            'user_id'   => $userReset->getUserId(),
            'token'    => $userReset->getToken(),
            'created_at'  => $userReset->getCreatedAt(),
        ]);
        
        if ($result) {
            return true;
        }
        return false;
    }

    public function updateUser(User $user)
    {
        $sql = "UPDATE `user`
                SET `user`.`name` = :name
                WHERE `user`.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'   => $user->getId(),
            'name' => $user->getName(),
        ]);

        if ($result) {
            return true;
        }
        return false;
    }

    public function updateUserStatus(User $user)
    {
        $sql = "UPDATE `user`
                SET `user`.`status` = :status
                WHERE `user`.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'     => $user->getId(),
            'status' => $user->getStatus(),
        ]);

        if ($result) {
            return true;
        }
        return false;
    }

    public function updateUserPassword(User $user)
    {
        $sql = "UPDATE `user`
                SET `user`.`password` = :password
                WHERE `user`.`id` = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $user->getId(),
            'password' => $user->getPassword(),
        ]);

        if ($result) {
            return true;
        }
        return false;
    }

    public function deleteUser($id)
    {

    }

    public function deleteUserResets($userId)
    {
        $sql = "DELETE FROM `user_reset`
                WHERE `user_reset`.`user_id` = :user_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['user_id' => $userId]);

        if ($result) {
            return true;
        }
        return false;
    }
}