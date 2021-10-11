<?php

namespace Rudashi\Traits;

use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Traversable;

trait Enumerable
{

    public function all(): array
    {
        return $this->items;
    }

    protected static function getFromArray($items): array
    {
        return $items;
    }

    protected static function getFromString($items, bool $split = false): array
    {
        try {
            $items = json_decode($items, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($items)) {
                throw new JsonException();
            }
            return $items;
        } catch (JsonException $e) {
            return $split ? str_split($items) : (array) $items;
        }
    }

    protected function getArrayItems($items): array
    {
        switch (true) {
            case is_array($items):
                return self::getFromArray($items);
            case $items instanceof EnumeratedInterface:
                return $items->all();
            case $items instanceof ArrayInterface:
                return $items->toArray();
            case $items instanceof Traversable:
                return iterator_to_array($items);
            case is_string($items):
                return self::getFromString($items);
            default:
                return (array) $items;
        }
    }

}