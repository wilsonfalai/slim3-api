<?php

namespace App\Services;

use App\Services\MessageRepository;
use RKA\ContentTypeRenderer\Renderer;

class Messages
{
    /**
     * Errors array
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Create new message service provider
     *
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->repository = $messageRepository;
    }

    /**
     * Get a named array of errors
     *
     * @return array $errors
     */
    public function getErrors()
    {
        return ['errors' => $this->errors];
    }

    /**
     * Set errors
     *
     * @param array|string $names
     */
    public function setErrors($names)
    {
        if (is_array($names)) {
            foreach ($names as $name) {
                if (array_key_exists($name, $this->repository->messages)) {
                    $this->errors[] = $this->repository->messages[$name];
                }
            }
        } else {
            if (array_key_exists($names, $this->repository->messages)) {
                $this->errors[] = $this->repository->messages[$names];
            }
        }
    }

    /**
     * Set a custom error
     *
     * @param array $errors
     */
    public function setStaticErrors($errors)
    {
        $this->errors[] = $errors;
    }

    /**
     * Check if there are any errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Respond with any errors
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     *
     * @return array
     */
    public function throwErrors($request, $response, Renderer $renderer)
    {
        if ($this->hasErrors()) {
            $errors = $this->getErrors();
            $response = $renderer->render($request, $response, $errors);
            return $response->withStatus($errors['errors'][0]['status']);
        }
    }

    /**
     * Get the details key of a specific message
     *
     * @param  string $name
     *
     * @return string
     */
    public function getDetails($name)
    {
        if (array_key_exists($name, $this->repository->messages)) {
            $message = ['message' => $this->repository->messages[$name]['detail']];
            return $message;
        }
        return false;
    }
}