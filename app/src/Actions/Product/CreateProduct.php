<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 17:34
 */

namespace App\Actions\Product;


use App\Domain\Product\ProductEntity;
use App\Middleware\ValidationRules;
use App\Response\PhpErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

/**
 * Class CreateProduct
 * @package App\Actions\Product
 */
class CreateProduct
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
            'description' => v::stringType(),
            'category_id' => ValidationRules::uuidValidator(),
            'price' => v::floatVal()->positive(),
            'weight' => v::intVal()->positive(),
            'stock_quantity' => v::intVal()->positive(),
            'image_url' => v::stringType()->length(1, 512),
            'image_thumb_url' => v::stringType()->length(1, 512),
            'active' => v::intVal()->in([0, 1])
        ];
    }


    public function __invoke(Request $request, Response $response)
    {


        $data = $request->getParsedBody();
        $name = $data['name'];
        $description = $data['description'];
        $category_id = $data['category_id'];
        $price = $data['price'];
        $weight = $data['weight'];
        $stock_quantity = $data['stock_quantity'];
        $image_url = $data['image_url'];
        $image_thumb_url = $data['image_thumb_url'];
        $active = $data['active'];

        $product = new ProductEntity();
        $product->id = $this->container->uuid->v5($this->container->uuid->v4(), $_SERVER['APP_SECRET']);
        $product->name = $name;
        $product->description = $description;
        $product->category_id = $this->container->uuid->toBinary($category_id);
        $product->category_id = $category_id;
        $product->price = $price;
        $product->weight = $weight;
        $product->stock_quantity = $stock_quantity;
        $product->image_url = $image_url;
        $product->image_thumb_url = $image_thumb_url;
        $product->active = $active;

        try
        {
            if($product->save()){
                $message  = $this->container->messages->getDetails('PRODUCT-0001');
                $data     = array_merge($message, $this->container->transformer->respondWithItem($product, $this->container->productTransformer));
                $response = $this->container->renderer->render($request, $response, $data);
                return $response->withStatus(201);

            } else{
                $this->container->messages->setErrors('PRODUCT-0003');
            }
            return $this->container->messages->throwErrors($request, $response, $this->container->renderer);

        }
        catch (\Throwable $t)
        {

            $customError = $this->container->phpErrorHandler;
            return $customError($request, $response,$t);

            #Se quisesse forçar o Slim Exception
            #throw new \Slim\Exception\SlimException($request, $response);

            #Uma outra maneira
            #return $response
            #    ->withStatus(500)
            #    ->withHeader('Content-Type', 'text/html')
            #    ->write('Something went wrong!');


        }



    }

}