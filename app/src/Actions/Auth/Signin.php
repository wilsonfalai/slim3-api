<?php

namespace App\Actions\Auth;

use App\Domain\Users\UserRepository;
use App\Domain\Users\UserTransformer;
use App\Services\Auth;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use Firebase\JWT\JWT;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class Signin
{    
    /**
     * @param UserRepository  $userRepository
     * @param UserTransformer $userTransformer
     * @param Auth            $auth
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param JWT             $jwt
     * @param Renderer        $renderer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer, Auth $auth, Messages $messages, Transformer $transformer, UUID $uuid, JWT $jwt, Renderer $renderer)
    {
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->auth            = $auth;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->jwt             = $jwt;
        $this->renderer        = $renderer;
    }

    /**
     * Signin using json web tokens
     *
     * @param  Request  $request
     * @param  Response $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $data     = $request->getParsedBody();
        $email    = $data['email'];
        $password = $data['password'];
        $authMode = isset($data['authMode']) && $data['authMode'] === 'native' ? 'native' : 'web';
        $user     = $this->userRepository->getUserByEmail($email);

        if ($user && intval($user->getStatus()) === 1) {
            if ($this->auth->validatePassword($user, $this->userRepository, $password)) {
                $user->setId($this->uuid->toString($user->getId()));
                $message = $this->messages->getDetails('USER-0010');
                $jwt = $this->auth->generateJWT($this->jwt, ['userId' => $user->getId()]);
                $data = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer), $jwt);
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(200);
            } else {
                $this->messages->setErrors('USER-0008');
            }
        } else {
            $this->messages->setErrors('USER-0008');
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
