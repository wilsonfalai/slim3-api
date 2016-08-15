<?php

namespace App\Domain\Users;

use League\Fractal\TransformerAbstract;
use App\Domain\Users\UserResetEntity;

class UserResetTransformer extends TransformerAbstract
{
    /**
     * List of fields to filter
     * On production we don\'t want to reveal things like tokens
     * that would allow somebody to reset a users\' password.
     * However we need them in development for testing.
     *
     * @var array $fields
     */
    public function __construct()
    {
        $this->filterFields = ['token'];
    }

    public function transform(UserResetEntity $userReset)
    {
        return $this->transformWithFieldFilter([
            'id'      => (string) $userReset->getId(),
            'token'   => (string) $userReset->getToken(),
            'created' => $userReset->getCreated(),
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
