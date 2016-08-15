<?php

namespace App\Actions\Users;

use App\Domain\Users\UserResetEntity;
use App\Domain\Users\UserRepository;
use App\Domain\Users\UserResetTransformer;
use App\Services\Auth;
use App\Services\Email;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUserReset
{
    /**
     * @param UserResetEntity      $userResetEntity
     * @param UserRepository       $userRepository
     * @param UserResetTransformer $userResetTransformer
     * @param Auth                 $auth
     * @param Email                $email
     * @param Messages             $messages
     * @param Transformer          $transformer
     * @param UUID                 $uuid
     * @param Renderer             $renderer
     * @param Twig                 $view
     */
    public function __construct(UserResetEntity $userResetEntity, UserRepository $userRepository, UserResetTransformer $userResetTransformer, Auth $auth, Email $email, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer, Twig $view)
    {
        $this->userResetEntity      = $userResetEntity;
        $this->userRepository       = $userRepository;
        $this->userResetTransformer = $userResetTransformer;
        $this->auth                 = $auth;
        $this->email                = $email;
        $this->messages             = $messages;
        $this->transformer          = $transformer;
        $this->uuid                 = $uuid;
        $this->renderer             = $renderer;
        $this->view                 = $view;
    }

    /**
     * Create a new user reset
     *
     * @param  Request  $request
     * @param  Response $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $data  = $request->getParsedBody();
        $email = $data['email'];
        $user  = $this->userRepository->getUserByEmail($email);

        // Maybe add some limit on request frequency here
        if ($user) {
            $userReset = $this->userResetEntity;
            $userReset->setId($this->uuid->v5($this->uuid->v4(), $_SERVER['APP_SECRET']));
            $userReset->setUserId($user->getId());
            $userReset->setToken($this->auth->generateToken());
            $userReset->setCreated(date('Y-m-d H:i:s'));

            if ($this->userRepository->saveUserReset($userReset)) {
                $userReset->setId($this->uuid->toString($userReset->getId()));
                $userReset->setUserId($this->uuid->toString($userReset->getUserId()));
                $emailPayload = [
                    'url'    => $_SERVER['CLIENT_URL'],
                    'userId' => $userReset->getUserId(),
                    'token'  => $userReset->getToken()
                ];
                $emailBodyHtml = $this->view->fetch('/email/userReset.html', $emailPayload);
                $emailBodyText = $this->view->fetch('/email/userReset.txt', $emailPayload);
                if ($this->email->send([$user->getEmail() => $user->getName()], 'Password Reset Instructions', $emailBodyHtml, $emailBodyText)) {
                    $message  = $this->messages->getDetails('USER-0011');
                    $data     = array_merge($message, $this->transformer->respondWithItem($userReset, $this->userResetTransformer));
                    $response = $this->renderer->render($request, $response, $data);
                    return $response->withStatus(201);
                } else {
                    $this->messages->setErrors('USER-0021');
                }
            } else {
                $this->messages->setErrors('USER-0020');
            }
        } else {
            $message  = $this->messages->getDetails('USER-0011');
            $response = $this->renderer->render($request, $response, $message);
            return $response->withStatus(201);
        }
        $this->messages->throwErrors($request, $response, $this->renderer);
    }
}
