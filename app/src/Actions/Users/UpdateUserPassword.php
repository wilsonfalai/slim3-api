<?php

namespace App\Actions\Users;

use App\Domain\Users\UserRepository;
use App\Domain\Users\UserTransformer;
use App\Services\Auth;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateUserPassword
{    
    /**
     * @param UserRepository  $userRepository
     * @param Auth            $auth
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer, Auth $auth, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer)
    {
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->auth            = $auth;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
    }

    /**
     * Update user password
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $data      = $request->getParsedBody();
        $id        = $this->uuid->toBinary($args['id']);
        $token     = $data['token'];
        $password  = $this->auth->hashPassword($data['password']);
        $userReset = $this->userRepository->getUserReset($id, $token);

        if ($userReset && $this->auth->validatePasswordResetExpiry($userReset->getCreatedAt())) {
            $user = $this->userRepository->getUser($id);
            $user->setPassword($password);
            if ($this->userRepository->updateUserPassword($user)) {
                $this->userRepository->deleteUserResets($id);
                $user->setId($this->uuid->toString($user->getId()));
                $message  = $this->messages->getDetails('USER-0014');
                $data     = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer));
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(200);
            } else {
                $this->messages->setErrors('USER-0022');
            }
        } elseif ($userReset) {
            $this->messages->setErrors('USER-0013');
        } else {
            $this->messages->setErrors('USER-0012');
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
