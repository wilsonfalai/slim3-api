<?php

namespace App\Actions\Users;

use App\Domain\Users\UserEntity;
use App\Domain\Users\UserRepository;
use App\Domain\Users\UserTransformer;
use App\Services\Auth;
use App\Services\Email;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUser
{
    /**
     * @param UserEntity      $userEntity
     * @param UserRepository  $userRepository
     * @param UserTransformer $userTransformer
     * @param Auth            $auth
     * @param Email           $email
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
     * @param Twig            $view
     */
    public function __construct(UserEntity $userEntity, UserRepository $userRepository, UserTransformer $userTransformer, Auth $auth, Email $email, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer, Twig $view)
    {
        $this->userEntity      = $userEntity;
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->auth            = $auth;
        $this->email           = $email;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
        $this->view            = $view;
    }

    /**
     * Create a new user
     *
     * @param  Request  $request
     * @param  Response $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $data       = $request->getParsedBody();
        $name       = $data['name'];
        $email      = $data['email'];
        $password   = $data['password'];
        $userExists = $this->userRepository->getUserByEmail($email);

        if ($userExists) {
            $this->messages->setErrors('USER-0001');
        } else {
            $user = $this->userEntity;
            $user->setId($this->uuid->v5($this->uuid->v4(), $_SERVER['APP_SECRET']));
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($this->auth->hashPassword($password));
            $user->setToken($this->auth->generateToken());
            $user->setStatus(0);
            $user->setCreated(date('Y-m-d H:i:s'));
            $user->setUpdated(date('Y-m-d H:i:s'));

            if ($this->userRepository->saveUser($user)) {
                $user->setId($this->uuid->toString($user->getId()));
                $emailPayload = [
                    'client' => $_SERVER['CLIENT_NAME'],
                    'url'    => $_SERVER['CLIENT_URL'],
                    'id'     => $user->getId(),
                    'email'  => $user->getEmail(),
                    'token'  => $user->getToken()
                ];
                $emailBodyHtml = $this->view->fetch('/email/userRegistration.html', $emailPayload);
                $emailBodyText = $this->view->fetch('/email/userRegistration.txt', $emailPayload);
                if ($this->email->send([$user->getEmail() => $user->getName()], 'User Registration', $emailBodyHtml, $emailBodyText)) {
                    $message  = $this->messages->getDetails('USER-0002');
                    $data     = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer));
                    $response = $this->renderer->render($request, $response, $data);
                    return $response->withStatus(201);
                } else {
                    $this->messages->setErrors('USER-0018');
                }                
            } else {
                $this->messages->setErrors('USER-0017');
            }
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
