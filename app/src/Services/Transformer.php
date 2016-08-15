<?php

namespace App\Services;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class Transformer
{
    /**
     * Create a new transformer service provider
     *
     * @param Manager    $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * Fractal respond with collection
     *
     * @param  array    $collection
     * @param  callable $callback
     *
     * @return array
     */
    public function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $data     = $this->fractal->createData($resource)->toArray();
        return $data;
    }

    /**
     * Fractal respond with item
     *
     * @param  array    $item
     * @param  callable $callback
     *
     * @return array
     */
    public function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $data     = $this->fractal->createData($resource)->toArray();
        return $data;
    }
}
