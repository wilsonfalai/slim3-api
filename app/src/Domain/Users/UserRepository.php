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
        $sql = "SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`password`, `users`.`token`, `users`.`status`, `users`.`created`
            FROM `users`
            WHERE `users`.`status` > 0
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
        $sql = "SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`password`, `users`.`token`, `users`.`status`, `users`.`created`
            FROM `users`
            WHERE `users`.`id` = :id";
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
        $sql = "SELECT `users`.`id`, `users`.`name`, `users`.`email`, `users`.`password`, `users`.`token`, `users`.`status`, `users`.`created`
            FROM `users`
            WHERE `users`.`email` = :email";
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
        $sql = "SELECT `userResets`.`id`, `userResets`.`userId`, `userResets`.`token`, `userResets`.`created`
            FROM `userResets`
            WHERE `userResets`.`userId` = :userId AND `userResets`.`token` = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'userId' => $userId,
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
        $sql = "INSERT INTO `slim3-api`.`users`
            (id, name, email, password, token, status, created) VALUES
            (:id, :name, :email, :password, :token, :status, :created)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $user->getId(),
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'password' => $user->getPassword(),
            'token'    => $user->getToken(),
            'status'   => $user->getStatus(),
            'created'  => $user->getCreated(),
        ]);
        
        if ($result) {
            return true;
        }
        return false;
    }

    public function saveUserReset(UserReset $userReset)
    {
        $sql = "INSERT INTO `slim3-api`.`userResets`
            (id, userId, token, created) VALUES
            (:id, :userId, :token, :created)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'       => $userReset->getId(),
            'userId'   => $userReset->getUserId(),
            'token'    => $userReset->getToken(),
            'created'  => $userReset->getCreated(),
        ]);
        
        if ($result) {
            return true;
        }
        return false;
    }

    public function updateUser(User $user)
    {
        $sql = "UPDATE `slim3-api`.`users`
                SET `users`.`name` = :name
                WHERE `users`.`id` = :id";
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
        $sql = "UPDATE `slim3-api`.`users`
                SET `users`.`status` = :status
                WHERE `users`.`id` = :id";
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
        $sql = "UPDATE `slim3-api`.`users`
                SET `users`.`password` = :password
                WHERE `users`.`id` = :id";
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
        $sql = "DELETE FROM `slim3-api`.`userResets`
                WHERE `userResets`.`userId` = :userId";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['userId' => $userId]);

        if ($result) {
            return true;
        }
        return false;
    }
}