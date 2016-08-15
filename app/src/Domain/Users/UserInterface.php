<?php

namespace App\Domain\Users;

use App\Domain\Users\UserEntity as User;
use App\Domain\Users\UserResetEntity as UserReset;

interface UserInterface
{

    public function getUsers($page = 0, $perPage = 15);

    public function getUser($id);

    public function getUserByName($name);

    public function getUserByEmail($email);

    public function getUserByUsername($username);

    public function getUserReset($userId, $token);

    public function saveUser(User $user);

    public function saveUserReset(UserReset $userReset);

    public function updateUser(User $user);

    public function updateUserStatus(User $user);

    public function updateUserPassword(User $user);

    public function deleteUser($id);

    public function deleteUserResets($userId);
}
