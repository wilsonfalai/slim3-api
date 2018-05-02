<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 18:57
 */

namespace App\Domain\Product;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class ProductTransformer
 * @package App\Domain\Product
 *
 * @property array filterFields;
 */
class ProductTransformer extends TransformerAbstract
{
    protected $container;

    public function __construct($container)
    {
        $this->filterFields = [];
        $this->container = $container;
    }

    public function transform(ProductEntity $product)
    {
        return $this->transformWithFieldFilter([
            #'id'                => (string) $client->getId(),
            #'first_name'        => (string) $client->getFirstName(),
             'id' => (string) $this->container->uuid->toString($product->id),
             'name' => (string) $product->name,
             'description' => (string) $product->description,
             'category_id' => (string) $this->container->uuid->toString($product->category_id),
             'price' => (double) $product->price,
             'weight' => (int) $product->weight,
             'stock_quantity' => (int) $product->stock_quantity,
             'image_url' => (string) $product->image_url,
             'image_thumb_url' => (string) $product->image_thumb_url,
             'active' => (int) $product->id
            
        ]);
    }

    /**
     * Filter fields
     *
     * @return Item
     */
    protected function transformWithFieldFilter($data)
    {
        if (is_null($this->filterFields) || $_SERVER['APP_ENV'] === 'dev') {
            return $data;
        }

        return array_diff_key($data, array_flip((array) $this->filterFields));
    }
}