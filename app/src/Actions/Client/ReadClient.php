<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31/03/18
 * Time: 16:48
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

class ReadClient implements ActionInterface
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
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param UUID            $uuid
     * @param Renderer        $renderer
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
            'id' => v::regex('/^\{?[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}\}?$/i'),
        ];
    }

    /**
     * Show a client
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
        $client = $this->clientRepository->getClient($id);

        if ($client) {
            $client->setId($this->uuid->toString($id));
            $data = $this->transformer->respondWithItem($client, $this->clientTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);//memory_get_usage()
        } else {
            $this->messages->setErrors('CLIENT-0003');
            return $this->messages->throwErrors($request, $response, $this->renderer);
        }
    }
}