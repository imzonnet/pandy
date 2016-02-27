<?php namespace App\Transforms;

abstract class Transformer
{
    public function transformCollection($collection) {
        return array_map([$this, 'transform'], $collection->toArray());
    }

    public abstract function transform(array $data);
}