<?php

namespace App\Services;

use Firebase\JWT\JWT;
use App\Domain\Users\UserEntity;
use App\Domain\Users\UserRepository;

class Auth
{
    /**
     * Generate a random token
     *
     * @return string
     */
    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * Generate a JWT
     *
     * @param  JWT   $jwt
     * @param  array $data
     *
     * @return array
     */
    public function generateJWT(JWT $jwt, $data)
    {
        $token = [
            'jti'  => base64_encode(mcrypt_create_iv(32)),
            'iss'  => $_SERVER['APP_PROTOCOL'] . $_SERVER['APP_DOMAIN'],
            'aud'  => 'http://' . $_SERVER['CLIENT_URL'],
            'iat'  => time(),
            'nbf'  => time(),
            'exp'  => time() + 604800, // one week
            'data' => $data
        ];
        $jwt = $jwt->encode($token, $_SERVER['APP_SECRET'], 'HS512');
        return ['token' => $jwt];
    }

    /**
     * Get the decoded JWT
     *
     * @param  JWT    $jwt
     * @param  string $token
     *
     * @return array|bool
     */
    public function decodeJWT(JWT $jwt, $token)
    {
        try {
            $token = JWT::decode($token, $_SERVER['APP_SECRET'], ['HS512']);
            return $token;
        } catch(\Exception $e){
            // log exception
            return false;
        }
    }

    /**
     * Hash user password
     *
     * @param  string $password
     *
     * @return string|bool
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT/*, $_SERVER['APP_PASS_ALGO_CONST']*/);
    }

    /**
     * Validate user password
     *
     * @param  UserEntity     $user
     * @param  UserRepository $userRepository
     * @param  string         $password
     *
     * @return bool
     */
    public function validatePassword(UserEntity $user, UserRepository $userRepository, $password)
    {
        if (password_verify($password, $user->getPassword())) {
            // Check if a newer hashing algorithm is available or the cost has changed
            if (password_needs_rehash($user->getPassword(), PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($newHash);
                $userRepository->updateUserPassword($user);
                echo 'yup';
            }
            return true;
        }
        return false;
    }

    /**
     * Validate amount of time since request issued
     *
     * @param  string $created
     *
     * @return bool
     */
    public function validatePasswordResetExpiry($created)
    {
        $created = strtotime($created);
        $now     = strtotime(date('Y-m-d H:i:s'));
        $diff    = round(($now - $created) / 60, 2);

        if (intval($diff) < 60) {//Minutos
            return true;
        }
        return false;
    }
}