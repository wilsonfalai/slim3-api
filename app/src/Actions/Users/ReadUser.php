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

final class ReadUser
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
     * Show a user
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $id   = $this->uuid->toBinary($args['id']);
        $user = $this->userRepository->getUser($id);

        if ($user) {
            $user->setId($this->uuid->toString($id));
            $data = $this->transformer->respondWithItem($user, $this->userTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        } else {
            $this->messages->setErrors('USER-0003');
            return $this->messages->throwErrors($request, $response, $this->renderer);
        }
    }
}
