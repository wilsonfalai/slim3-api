<?php

namespace App\Actions\Users;

use App\Domain\Users\UserRepository;
use App\Domain\Users\UserTransformer;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateUserStatus
{    
    /**
     * @param UserRepository  $userRepository
     * @param UserTransformer $userTransformer
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer)
    {
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
    }

    /**
     * Update user status
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  string   $id
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $data  = $request->getParsedBody();
        $id    = $this->uuid->toBinary($args['id']);
        $token = $data['token'];
        $user  = $this->userRepository->getUser($id);

        if ($user && $user->getToken() === $token && intval($user->getStatus()) === 0) {
            $user->setStatus(1);
            if ($this->userRepository->updateUserStatus($user)) {
                $user->setId($this->uuid->toString($id));
                $message  = $this->messages->getDetails('USER-0006');
                $data     = array_merge($message, $this->transformer->respondWithItem($user, $this->userTransformer));
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(200);
            } else {
                $this->messages->setErrors('USER-0019');    
            }
        } else {
            $this->messages->setErrors('USER-0007');
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
