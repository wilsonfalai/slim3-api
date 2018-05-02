<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/18
 * Time: 19:41
 */

namespace App\Domain\Category;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->filterFields = [];
    }

    public function transform(CategoryEntity $product)
    {
        return $this->transformWithFieldFilter([
            'id' => (string) $product->id,
            'name' => (string) $product->name,
            'parent_id' => $product->parent_id ? (string) $product->parent_id : null,

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