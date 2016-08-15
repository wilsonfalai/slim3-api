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

final class ReadUsers
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
        $page  = $request->getParam('page');
        $page  = (isset($page) && intval($page) > 0) ? intval($page) - 1 : 0;
        $users = $this->userRepository->getUsers($page);

        if ($users) {
            $results = [];
            foreach ($users as $user) {
                $user->setId($this->uuid->toString($user->getId()));
                $results[] = $user;
            }
            $data = $this->transformer->respondWithCollection($results, $this->userTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        } else {
            $this->messages->setErrors('USER-0016');
            $this->messages->throwErrors($request, $response);
        }
    }
}
