<?php

namespace Rudashi\Traits;

use Rudashi\Contracts\EnumeratedInterface;
use Traversable;

trait Enumerable
{

    public function all(): array
    {
        return $this->items;
    }

    private function getArray($items): array
    {
        switch (true) {
            case is_array($items):
                return $items;
            case $items instanceof EnumeratedInterface:
                return $items->all();
            case $items instanceof Traversable:
                return iterator_to_array($items);
            default:
                return $items !== null ? [$items] : [];
        }
    }

}