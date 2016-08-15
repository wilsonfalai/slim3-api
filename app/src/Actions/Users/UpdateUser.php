<?php

namespace App\Actions\Users;

use App\Domain\Users\UserEntity;
use App\Domain\Users\UserRepository;
use App\Domain\Users\UserTransformer;
use App\Services\Auth;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateUser
{    
    /**
     * @param UserEntity      $userEntity
     * @param UserRepository  $userRepository
     * @param UserTransformer $userTransformer
     * @param Auth            $auth
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
     */
    public function __construct(UserEntity $userEntity, UserRepository $userRepository, UserTransformer $userTransformer, Auth $auth, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer)
    {
        $this->userEntity      = $userEntity;
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->auth            = $auth;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
    }

    /**
     * Update user
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $data        = $request->getParsedBody();
        $id          = $this->uuid->toBinary($args['id']);
        $name        = isset($data['name']) ? $data['name'] : null;
        $password    = isset($data['password']) ? $data['password'] : null;
        $newPassword = isset($data['newPassword']) ? $data['newPassword'] : null;
        $user        = $this->userRepository->getUser($id);

        if ($user) {
            if ($password != null && $newPassword != null && $this->auth->validatePassword($user, $this->userRepository, $password)) {
                $user->setPassword($this->auth->hashPassword($newPassword));
                if (!$this->userRepository->updateUserPassword($user)) {
                    $this->messages->setErrors('USER-0022');
                    return $this->messages->throwErrors($request, $response, $this->renderer);
                }
            } elseif ($password === null && $newPassword) {
                $this->messages->setErrors('USER-0015');
                return $this->throwErrors($request, $response, $this->renderer);
            } elseif (!$this->auth->validatePassword($user, $this->userRepository, $password) && $newPassword != null) {
                $this->messages->setErrors('USER-0004');
                return $this->throwErrors($request, $response, $this->renderer);
            }
            
            $user->setName($name);
            if ($this->userRepository->updateUser($user)) {
                $user->setId($this->uuid->toString($id));
                $message  = $this->messages->getDetails('USER-0005');
                $data     = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer));
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(200);
            } else {
                $this->messages->setErrors('USER-0023');
                return $this->messages->throwErrors($request, $response, $this->renderer);
            }
            
        } else {
            $this->messages->setErrors('USER-0003');
            return $this->messages->throwErrors($request, $response, $this->renderer);
        }
    }
}
