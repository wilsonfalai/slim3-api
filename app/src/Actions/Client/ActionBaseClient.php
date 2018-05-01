<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 13:06
 */

namespace App\Actions\Client;

use App\Actions\ActionBase;
use App\Domain\Client\ClientEntity;
use App\Domain\Client\ClientRepository;
use App\Domain\Client\ClientTransformer;
use App\Services\Auth;
use App\Services\Email;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use Psr\Container\ContainerInterface;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Views\Twig;


class ActionBaseClient extends ActionBase
{
    protected $container;

    protected $uuid;

    /**
     * @param ClientEntity      $clientEntity
     * @param ClientRepository  $clientRepository
     * @param ClientTransformer $clientTransformer
     * @param Auth            $auth
     * @param Email           $email
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
     * @param Twig            $view
     */
    public function __construct(ClientEntity $clientEntity, ClientRepository $clientRepository, ClientTransformer $clientTransformer, Auth $auth, Email $email, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer, Twig $view)
    {
        $this->userEntity      = $clientEntity;
        $this->userRepository  = $clientRepository;
        $this->userTransformer = $clientTransformer;
        $this->auth            = $auth;
        $this->email           = $email;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
        $this->view            = $view;
    }


}