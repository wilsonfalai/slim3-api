<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02/05/18
 * Time: 10:43
 */

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class RouterMiddleware
{

    protected $container;

    /**
     * RouterMiddleware constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        if(!$route){
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $next($request, $response);
    }
}