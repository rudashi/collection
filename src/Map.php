<?php

namespace Rudashi;

use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Traits\Arrayable;
use Traversable;
use TypeError;

class Map implements EnumeratedInterface, ArrayInterface
{

    use Arrayable;

    protected array $items = [];

    public function __construct($items = [])
    {
        $this->items = $this->getArray($items);
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

    public static function from($items = null, callable $callback = null): self
    {
        if (is_string($items)) {
            try {
                $items = json_decode($items, true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($items)) {
                    throw new JsonException();
                }
            } catch (JsonException $e) {
                $items = str_split($items);
            }
        }
        if (is_int($items) && $items > -1) {
            $items = range(0, $items - 1);
        }
        if (is_array($items)) {
            return $callback ? (new self($items))->map($callback) : new self($items);
        }
        if ($items instanceof self) {
            return $callback ? $items->map($callback) : $items;
        }
        throw new TypeError(gettype($items).' is not iterable');
    }

    /**
     * Returns a new Map that contains the keys.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/keys
     *
     * @return static
     */
    public function keys(): self
    {
        return new static(array_keys($this->items));
    }

    /**
     * Returns a new Map as a result of passed function on every item.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/map
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): self
    {
        $keys = array_keys($this->items );
        $elements = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $elements) ?: []);
    }

    /**
     * Returns a new Map that contains the values with reset keys.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/values
     *
     * @return static
     */
    public function values(): self
    {
        return new static(array_values($this->items));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return $this->map(function ($value) {
            return $value instanceof ArrayInterface ? $value->toArray() : $value;
        })->all();
    }

}