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

class UpdateToken
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
     * Update a json web token
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $id        = $this->uuid->toBinary($args['id']);
        $user      = $this->userRepository->getUser($id);
        list($jwt) = sscanf($request->getHeader('Authorization')[0], 'Bearer %s');
        $token     = $this->auth->decodeJWT($this->jwt, $jwt);
        if ($user && $jwt && $token) {
            $tokenUserId = $this->uuid->toBinary($token->data->userId);          
            if ($id === $tokenUserId) {
                $user->setId($this->uuid->toString($user->getId()));
                $message = $this->messages->getDetails('AUTH-0005');
                $jwt = $this->auth->generateJWT($this->jwt, ['userId' => $user->getId()]);
                $data = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer), $jwt);
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(200);
            } else {
                $this->messages->setErrors('AUTH-0002');
            }
        } else {
            $this->messages->setErrors('AUTH-0004');
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
