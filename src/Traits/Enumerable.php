<?php

namespace Rudashi\Traits;

use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Traversable;

trait Enumerable
{

    public function all(): array
    {
        return $this->items;
    }

    protected function getArray($items): array
    {
        return $items;
    }

    protected function getArrayItems($items): array
    {
        switch (true) {
            case is_array($items):
                return $this->getArray($items);
            case $items instanceof EnumeratedInterface:
                return $items->all();
            case $items instanceof ArrayInterface:
                return $items->toArray();
            case $items instanceof Traversable:
                return iterator_to_array($items);
            default:
                return (array) $items;
        }
    }

}