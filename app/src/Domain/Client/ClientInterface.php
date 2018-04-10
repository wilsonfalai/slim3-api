<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/03/18
 * Time: 15:17
 */

namespace App\Domain\Client;


interface ClientInterface
{
    public function getClients($page = 0, $perPage = 15);

    public function getClientByEmail($email);

    public function saveClient(ClientEntity $client);
}