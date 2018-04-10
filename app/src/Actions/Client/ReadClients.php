<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 13:05
 */

namespace App\Actions\Client;

use App\Actions\ActionInterface;
use App\Domain\Client\ClientRepository;
use App\Domain\Client\ClientTransformer;
use App\Services\Messages;
use App\Services\Transformer;
use App\Services\UUID;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

final class ReadClients implements ActionInterface
{
    protected $clientRepository;
    protected $clientTransformer;
    protected $messages;
    protected $transformer;
    protected $uuid;
    protected $renderer;

    /**
     * @param ClientRepository  $clientRepository
     * @param ClientTransformer $clientTransformer
     * @param Messages          $messages
     * @param Transformer       $transformer
     * @param UUID              $uuid
     * @param Renderer          $renderer
     */
    public function __construct(ClientRepository $clientRepository, ClientTransformer $clientTransformer, Messages $messages, Transformer $transformer, UUID $uuid, Renderer $renderer)
    {
        $this->clientRepository  = $clientRepository;
        $this->clientTransformer = $clientTransformer;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->uuid            = $uuid;
        $this->renderer        = $renderer;
    }

    /**
     * Validation Rules
     *
     * @return array
     */
    public static function getValidationRules(){
        return [
            'page' => v::optional(v::intVal())
        ];
    }

    /**
     * Show a clients
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
        $clients = $this->clientRepository->getClients($page);

        if ($clients) {
            $results = [];
            foreach ($clients as $client) {
                $client->setId($this->uuid->toString($client->getId()));
                $results[] = $client;
            }
            $data = $this->transformer->respondWithCollection($results, $this->clientTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        } else {
            $this->messages->setErrors('CLIENT-0016');
            return $this->messages->throwErrors($request, $response, $this->renderer);
        }
    }
}