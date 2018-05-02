<?php
//php -S localst:8080 display_errors=0

/**
 * Users
 */
$app->group('/users', function() {

    $this->post('', App\Actions\Users\CreateUser::class)
        ->setArguments(['validators' => \App\Actions\Users\CreateUser::getValidationRules()]);

    $this->get('', App\Actions\Users\ReadUsers::class)
        ->setArguments(['validators' => \App\Actions\Users\ReadUsers::getValidationRules()]);

    $this->post('/lost-password', App\Actions\Users\CreateUserReset::class)
        ->setArguments(['state' => 'anonymous', 'validators' => ['email']]);

    $this->group('/{id}', function() {

        $this->get('', App\Actions\Users\ReadUser::class)
            ->setArguments(['validators' => \App\Actions\Users\ReadUser::getValidationRules()]);

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
 * Product
 */
$app->group('/products', function() {

    $this->post('', App\Actions\Product\CreateProduct::class)
        ->setArguments(['validators' => \App\Actions\Product\CreateProduct::getValidationRules()]);

    /*$this->get('', \App\Actions\Product\ReadProducts::class)
        ->setArguments(['validators' => \App\Actions\Product\ReadProducts::getValidationRules()]);

    $this->group('/{id}', function() {
        $this->get('', App\Actions\Product\ReadProduct::class)
            ->setArguments(['validators' => \App\Actions\Product\ReadProduct::getValidationRules()]);
    });*/
});

/**
 * Category
 */
$app->group('/categories', function() {

    $this->post('', App\Actions\Category\CreateCategory::class)
        ->setArguments(['validators' => \App\Actions\Category\CreateCategory::getValidationRules()]);

    /*$this->get('', \App\Actions\Category\ReadCategories::class)
        ->setArguments(['validators' => \App\Actions\Category\ReadCategories::getValidationRules()]);

    $this->group('/{id}', function() {
        $this->get('', App\Actions\Category\ReadCategory::class)
            ->setArguments(['validators' => \App\Actions\Category\ReadCategory::getValidationRules()]);
    });*/
});


/**
 * Client
 */
$app->group('/clients', function() {

    $this->post('', App\Actions\Client\CreateClient::class)
        ->setArguments(['validators' => \App\Actions\Client\CreateClient::getValidationRules()]);

    $this->get('', \App\Actions\Client\ReadClients::class)
        ->setArguments(['validators' => \App\Actions\Client\ReadClients::getValidationRules()]);

    $this->group('/{id}', function() {
        $this->get('', App\Actions\Client\ReadClient::class)
            ->setArguments(['validators' => \App\Actions\Client\ReadClient::getValidationRules()]);
    });

});

/**
 * Authentication
 */
$app->group('/auth', function() {
    $this->post('', App\Actions\Auth\Signin::class)
        ->setArguments(['state' => 'anonymous', 'validators' => App\Actions\Auth\Signin::getValidationRules()]);

    $this->patch('/{id}', App\Actions\Auth\UpdateToken::class)
        ->setArguments(['validators' => ['id' => ['uuid']]]);
});
