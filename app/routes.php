<?php

/**
 * Users
 */
$app->group('/users', function() {

    $this->post('', App\Actions\Users\CreateUser::class)
        ->setArguments(['state' => 'anonymous', 'validators' => ['name', 'email', 'password']]);

    $this->get('', App\Actions\Users\ReadUsers::class)
        ->setArguments(['validators' => ['page' => ['int', true]]]);

    $this->post('/lost-password', App\Actions\Users\CreateUserReset::class)
        ->setArguments(['state' => 'anonymous', 'validators' => ['email']]);

    $this->group('/{id}', function() {

        $this->get('', App\Actions\Users\ReadUser::class)
            ->setArguments(['validators' => ['id' => ['uuid']]]);

        $this->patch('', App\Actions\Users\UpdateUser::class)
            ->setArguments(['validators' => [
                'id' => ['uuid'],
                'name' => [false, true],
                'password' => [false, true],
                'newPassword' => ['password', true, 'New Password']
            ]]);

        $this->patch('/status', App\Actions\Users\UpdateUserStatus::class)
            ->setArguments(['state' => 'anonymous', 'validators' => ['id' => ['uuid'], 'token']]);

        $this->patch('/password', App\Actions\Users\UpdateUserPassword::class)
            ->setArguments(['state' => 'anonymous', 'validators' => ['id' => ['uuid'], 'token', 'password']]);

    });
});

/**
 * Authentication
 */
$app->group('/auth', function() {
    $this->post('', App\Actions\Auth\Signin::class)
        ->setArguments(['state' => 'anonymous', 'validators' => ['email', 'password', 'authMode' => [false, true]]]);

    $this->patch('/{id}', App\Actions\Auth\UpdateToken::class)
        ->setArguments(['validators' => ['id' => ['uuid']]]);
});
