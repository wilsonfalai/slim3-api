<?php

/**
 * Fetch the container
 */
$container = $app->getContainer();

/**
 * PDO
 */
$container['db'] = function ($c) {
    // $db = $container['settings']['database'];
    $pdo = new PDO("mysql:host=" . $_SERVER['DB_HOST'] . ";dbname=" . $_SERVER['DB_NAME'],
        $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

/**
 * Content Renderer
 */
$container['renderer'] = function ($c) {
    return new RKA\ContentTypeRenderer\Renderer;
};

/**
 * Swiftmailer
 */
$container['mailer'] = function ($c) {
    // $email = $container['settings']['email'];
    $transport = Swift_SmtpTransport::newInstance($_SERVER['SMTP_SERVER'], $_SERVER['SMTP_PORT'], 'ssl')
        ->setUsername($_SERVER['SMTP_USER'])
        ->setPassword($_SERVER['SMTP_PASS']);
    $mailer = Swift_Mailer::newInstance($transport);
    return $mailer;
};

/**
 * Fractal
 */
$container['fractal'] = function ($c) {
    return new League\Fractal\Manager;
};

// Note that protected closures do not get access to the container
$container['collection'] = $container->protect(function ($collection, $callback) {
    return new League\Fractal\Resource\Collection($collection, $callback);
});

$container['item'] = $container->protect(function ($item, $callback) {
    return new League\Fractal\Resource\Item($item, $callback);
});

/**
 * JSON Web Tokens
 */
$container['jwt'] = function ($c) {
    return new Firebase\JWT\JWT;
};

/**
 * Twig Templates
 */
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig(__DIR__ . '/views', [
        'cache' => __DIR__ . '/../cache/twig',
        'debug' => $_SERVER['APP_DEBUG']
    ]);
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};

/*
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};
*/

/**
 * Actions
 */
$container[App\Actions\Auth\Signin::class] = function ($c) {
    return new App\Actions\Auth\Signin($c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('jwt'), $c->get('renderer'));
};

$container[App\Actions\Auth\UpdateToken::class] = function ($c) {
    return new App\Actions\Auth\UpdateToken($c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('jwt'), $c->get('renderer'));
};

$container[App\Actions\Users\CreateUser::class] = function ($c) {
    return new App\Actions\Users\CreateUser($c->get('userEntity'), $c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('email'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'), $c->get('view'));
};

$container[App\Actions\Users\CreateUserReset::class] = function ($c) {
    return new App\Actions\Users\CreateUserReset($c->get('userResetEntity'), $c->get('userRepository'), $c->get('userResetTransformer'), $c->get('auth'), $c->get('email'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'), $c->get('view'));
};

$container[App\Actions\Users\ReadUser::class] = function ($c) {
    return new App\Actions\Users\ReadUser($c->get('userRepository'), $c->get('userTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'));
};

$container[App\Actions\Users\ReadUsers::class] = function ($c) {
    return new App\Actions\Users\ReadUsers($c->get('userRepository'), $c->get('userTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'));
};

$container[App\Actions\Users\UpdateUser::class] = function ($c) {
    return new App\Actions\Users\UpdateUser($c->get('userEntity'), $c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'));
};

$container[App\Actions\Users\UpdateUserPassword::class] = function ($c) {
    return new App\Actions\Users\UpdateUserPassword($c->get('userRepository'), $c->get('userTransformer'), $c->get('auth'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'));
};

$container[App\Actions\Users\UpdateUserStatus::class] = function ($c) {
    return new App\Actions\Users\UpdateUserStatus($c->get('userRepository'), $c->get('userTransformer'), $c->get('messages'), $c->get('transformer'), $c->get('uuid'), $c->get('renderer'));
};

/**
 * Domain
 */
$container['userEntity'] = $container->factory(function ($c) {
    return new App\Domain\Users\UserEntity;
});

$container['userRepository'] = function ($c) {
    return new App\Domain\Users\UserRepository($c->get('db'));
};

$container['userResetEntity'] = $container->factory(function ($c) {
    return new App\Domain\Users\UserResetEntity;
});

$container['userResetTransformer'] = function ($c) {
    return new App\Domain\Users\UserResetTransformer;
};

$container['userTransformer'] = function ($c) {
    return new App\Domain\Users\UserTransformer;
};


/**
 * Middleware
 */
$container[App\Middleware\AuthMiddleware::class] = function ($c) {
    return new App\Middleware\AuthMiddleware($c->get('userRepository'), $c->get('auth'), $c->get('messages'), $c->get('uuid'), $c->get('jwt'), $c->get('renderer'));
};

$container[App\Middleware\ValidationMiddleware::class] = function ($c) {
    return new App\Middleware\ValidationMiddleware($c->get('validationRules'), $c->get('messages'), $c->get('renderer'));
};

$container['validationRules'] = function ($c) {
    return new App\Middleware\ValidationRules;
};

/**
 * Services
 */
$container['auth'] = function ($c) {
    return new App\Services\Auth;
};

$container['email'] = function ($c) {
    return new App\Services\Email($c->get('mailer'));
};

$container['messageRepository'] = function ($c) {
    return new App\Services\MessageRepository;
};

$container['messages'] = function ($c) {
    return new App\Services\Messages($c->get('messageRepository'));
};

$container['transformer'] = function ($c) {
    return new App\Services\Transformer($c->get('fractal'));
};

$container['uuid'] = function ($c) {
    return new App\Services\UUID;
};
