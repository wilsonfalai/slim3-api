<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02/05/18
 * Time: 11:13
 */
namespace App\Response;

class PhpErrorHandler
{

    protected $message;

    protected $code;
    /**
     * PhpErrorHandler constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $request
     * @param $response
     * @param \Throwable $exception
     * @return mixed
     */
    public function __invoke($request, $response, $exception) {


        $this->message = $this->filter($exception->getMessage());

        return $response
            ->withStatus(501)
            ->withHeader('Content-Type', 'text/html')//'application/json'//'text/html'//'application/problem+json'
            ->write($this->message);
    }

    private function filter($string){
        return preg_replace('/[[:^print:]]/', ' ', $string);
    }
}