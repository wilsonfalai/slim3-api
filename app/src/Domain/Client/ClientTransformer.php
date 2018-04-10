<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 16:52
 */

namespace App\Domain\Client;


use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    /**
     * List of fields to filter
     * On production we don\'t want to reveal things like tokens
     * that would allow somebody to reset a clients\' password.
     * However we need them in development for testing.
     *
     * @var array $fields
     */
    public function __construct()
    {
        $this->filterFields = ['token'];
    }

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ClientEntity $client)
    {
        return $this->transformWithFieldFilter([
            'id'                => (string) $client->getId(),
            'first_name'        => (string) $client->getFirstName(),
            'last_name'         => (string) $client->getLastName(),
            'email'             => (string) $client->getEmail(),
            'birth_date'        => $client->getBirthDate(),
            'phone'             => (string) $client->getPhone(),
            'document_number'   => (string) $client->getDocumentNumber(),
            //'token'   => (string) $client->getToken(),
            'created_at'        => $client->getCreatedAt(),
            'updated_at'        => $client->getUpdatedAt(),
        ]);
    }

    /**
     * Filter fields
     *
     * @return League\Fractal\ItemResource
     */
    protected function transformWithFieldFilter($data)
    {
        if (is_null($this->filterFields) || $_SERVER['APP_ENV'] === 'dev') {
            return $data;
        }

        return array_diff_key($data, array_flip((array) $this->filterFields));
    }
}