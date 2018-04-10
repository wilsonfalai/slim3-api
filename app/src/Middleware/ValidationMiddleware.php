<?php

namespace App\Middleware;

use App\Middleware\ValidationRules;
use App\Services\Messages;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validators that throw exceptions outputted by RKA\ContentTypeRenderer\Renderer
 *
 * This Middleware takes request key and validator name and loops through validators
 * This way it stays as middleware and;
 * only validates what you want it to and;
 * accepts request keys that don't match the exact validator name
 */
class ValidationMiddleware
{
    /**
     * Validators
     *
     * @var array
     */
    protected $validators = [];

    /**
     * The translator to use for the exception message
     *
     * @var null|callable
     */
    protected $translator = null;

    /**
     * Create new Validator service provider
     *
     * @param ValidationRules           $validationRules
     * @param Messages                  $messages
     * @param Renderer                  $renderer
     */
    public function __construct($validationRules, Messages $messages, Renderer $renderer)
    {
        $this->validationRules = $validationRules;
        $this->messages        = $messages;
        $this->renderer        = $renderer;
    }

    /**
     * Validation middleware invokable class
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  callable $next
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next)
    {

        $this->setValidators($request);
        //die(var_dump($this->validators));
        // $this->translator      = $translator;

        //die(var_dump($this->validators));

        //Validate every parameter in the validators array
        /*foreach ($this->validators as $key => $validator) {
            if (is_array($validator)) {
                // [string $validationRule , bool $optional , string $name]
                $param          = $this->getParam($request, $key);
                $validationRule = isset($validator[0]) && $validator[0] ? $validator[0] . 'Validator' : $key . 'Validator';
                $optional       = isset($validator[1]) && $validator[1] ? true : false;
                $name           = isset($validator[2]) ? $validator[2] : $key;
                $validator      = $this->validationRules->$validationRule(ucfirst($name), $optional);
            } else {
                $param          = $this->getParam($request, $validator);
                $validationRule = $validator . 'Validator';
                $optional       = false;
                $name           = $validator;
                $validator      = $this->validationRules->$validationRule(ucfirst($validator));
            }

            try {
                $validator->assert($param);
            } catch (NestedValidationException $exception) {
                if ($this->translator) {
                    $exception->setParam('translator', $this->translator);
                }

                $messages = $exception->getMessages();
                foreach ($messages as $message) {
                    $this->messages->setStaticErrors([
                        'code'   => 'VAL-0001',
                        'status' => 400,
                        'title'  => 'Validation error',
                        'detail' => $message
                    ]);
                }
            }
        }*/

        foreach ($this->validators as $key => $validator) {

            //$param = is_array($validator) ? $this->getParam($request, $key) : $this->getParam($request, $validator);
            $param = $this->getParam($request, $key);
            try {
                $validator->assert($param);
            } catch (NestedValidationException $exception) {
                if ($this->translator) {
                    $exception->setParam('translator', $this->translator);
                }

                $messages = $exception->getMessages();
                foreach ($messages as $message) {
                    $this->messages->setStaticErrors([
                        'code'   => 'VAL-0001',
                        'status' => 400,
                        'title'  => 'Validation error',
                        'detail' => $message
                    ]);
                }
            }

        }



        if ($this->messages->hasErrors()) {
            $errors = $this->messages->getErrors();
            $response = $this->renderer->render($request, $response, $errors);
            return $response->withStatus(400);
        } else {
            return $next($request, $response);
        }
    }

    /**
     * Get validators
     *
     * @param array $validators
     */
    public function getValidators($validators)
    {
        return $this->validators;
    }

    /**
     * Set validators
     *
     * @param Request $request
     */
    public function setValidators(Request $request)
    {
        $route = $request->getAttribute('route');
        $validators = $route->getArgument('validators');

        if (is_array($validators) || $validators instanceof ArrayAccess) {
            $this->validators = $validators;
        } elseif (is_null($validators)) {
            $this->validators = [];
        }
    }

    /**
     * Get translator
     *
     * @return callable The translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Set translator
     *
     * @param callable $translator The translator.
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get param
     *
     * @param  ServerRequestInterface $request
     * @param  string                 $key
     *
     * @return string|bool
     */
    public function getParam($request, $key)
    {
        $param = $request->getParam($key);
        if ($param) {
            return $param;
        } else {
            $route = $request->getAttribute('route');
            $param = $route->getArgument($key);
            return $param ? $param : null;
        }
    }
}
