<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 02:02
 */
namespace App\Actions\Client;

use App\Actions\ActionInterface;
use App\Domain\Client\ClientEntity;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

final class CreateClient extends ActionBaseClient implements ActionInterface
{
    /**
     * Regras de validação para cadastrar um novo cliente
     *
     * @return array
     */
    public static function getValidationRules(){
        return [
            'fisrt_name' => v::length(3, 64),
            'last_name' => v::optional(v::length(2, 128)),
            'email' => v::email(),
            'birth_date' => v::optional(v::date()),
            'password' => v::length(8, 32),
            'phone' => v::optional(v::phone()),
            'status' => v::optional(v::intVal()),
            'document_number' => v::optional(v::oneOf(
                v::cpf(),
                v::cnpj()
            )),
        ];
    }

    /**
     * Create a new client
     *
     * @param  Request  $request
     * @param  Response $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $data               = $request->getParsedBody();
        $fisrt_name         = $data['fisrt_name'];
        $lastName           = $data['last_name'];
        $email              = $data['email'];
        $birth_date         = $data['birth_date'];
        $status             = $request->getQueryParam('active', 1);
        $phone              = $data['phone'];
        $document_number    = $data['document_number'];
        $password           = $data['password'];
        $clientExists         = $this->clientRepository->getClientByEmail($email);


        if ($clientExists) {
            $this->messages->setErrors('CLIENT-0001');
        } else {
            $client = $this->clientEntity;
            $client->setId($this->uuid->v5($this->uuid->v4(), $_SERVER['APP_SECRET']));
            $client->setFirstName($fisrt_name);
            $client->setLastName($lastName);
            $client->setEmail($email);
            $client->setBirthDate($birth_date);
            $client->setPhone($phone);
            $client->setDocumentNumber($document_number);
            $client->setPassword($this->auth->hashPassword($password));
            $client->setToken($this->auth->generateToken());
            $client->setStatus($status);
            $client->setCreatedAt(date('Y-m-d H:i:s'));
            $client->setUpdatedAt(date('Y-m-d H:i:s'));

            if ($this->clientRepository->saveClient($client)) {
                $client->setId($this->uuid->toString($client->getId()));

                $message  = $this->messages->getDetails('CLIENT-0002');
                $data     = array_merge($message, $this->transformer->respondWithItem($client, $this->clientTransformer));
                $response = $this->renderer->render($request, $response, $data);
                return $response->withStatus(201);

            } else {
                $this->messages->setErrors('CLIENT-0017');
            }
        }
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}