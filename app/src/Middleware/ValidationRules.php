<?php

namespace App\Middleware;

use Respect\Validation\Validator as v;

class ValidationRules
{
    public function intValidator($name, $optional = false)
    {
        $intValidator = $optional ? v::optional(v::intVal())->setName($name) : v::intVal()->setName($name);
        return $intValidator;
    }

    public function uuidValidator($name, $optional = false)
    {
        $uuidValidator = $optional ? v::optional(v::regex('/^\{?[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}\}?$/i'))->setName($name) : v::regex('/^\{?[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}\}?$/i')->setName($name);
        return $uuidValidator;
    }

    public function nameValidator($name, $optional = false)
    {
        $nameValidator = $optional ? v::optional(v::alnum()->length(2, 75))->setName($name) : v::alnum()->length(2, 75)->setName($name);
        return $nameValidator;
    }

    public function emailValidator($name, $optional = false)
    {
        $emailValidator = $optional ? v::optional(v::email())->setName($name) : v::email()->setName($name);
        return $emailValidator;
    }

    public function passwordValidator($name, $optional = false)
    {
        $passwordValidator = $optional ? v::optional(v::length(8, 55))->setName($name) : v::length(8, 55)->setName($name);
        return $passwordValidator;
    }

    public function tokenValidator($name)
    {
        $tokenValidator = v::xdigit()->length(32, 32)->setName($name);
        return $tokenValidator;
    }

    public function authModeValidator($name, $optional = false)
    {
        $authModeValidator = $optional ? v::optional(v::in(['native', 'web'], true))->setName($name) : v::in(['native', 'web'], true)->setName($name);
        return $authModeValidator;
    }
}