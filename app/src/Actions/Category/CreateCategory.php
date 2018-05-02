<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 19:35
 */

namespace App\Actions\Category;

use App\Domain\Category\CategoryEntity;
use App\Middleware\ValidationRules;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class CreateCategory
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * CreateProduct constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Regra de validação
     * @return array
     */
    public static function getValidationRules(){
        return[
            'name' => v::stringType()->length(3, 64),
            'parent_id' => ValidationRules::uuidValidator(true),
        ];
    }

    public function __invoke(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $name = $data['name'];
        $parent_id = $data['parent_id'];


        $category = new CategoryEntity();
        $category->id = $this->container->uuid->v5($this->container->uuid->v4(), $_SERVER['APP_SECRET']);
        $category->name = $name;
        $category->parent_id = $parent_id;

        if($category->save()){

            $category->id =$this->container->uuid->toString($category->id);
            $message  = $this->container->messages->getDetails('CATEGORY-0001');
            $data     = array_merge($message, $this->container->transformer->respondWithItem($category, $this->container->categoryTransformer));
            $response = $this->container->renderer->render($request, $response, $data);
            return $response->withStatus(201);

        } else{
            $this->container->messages->setErrors('CATEGORY-0003');
        }

        return $this->container->messages->throwErrors($request, $response, $this->container->renderer);
    }
}