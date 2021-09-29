<?php

namespace Rudashi;

use Exception;
use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Traits\Arrayable;
use Traversable;
use TypeError;

/**
 * @property int $length
 */
class Map implements EnumeratedInterface, ArrayInterface
{
    use Arrayable;

    private ?int $length = null;
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
     * Returns the number of elements.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/length
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns a new instance that contains the keys.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/keys
     *
     * @return static
     */
    public function keys(): self
    {
        return new static(array_keys($this->items));
    }

    /**
     * Returns a new instance as a result of passed function on every item.
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
     * Push one or more items onto the end of the collection.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/push
     *
     * @param  mixed $elements
     * @return static
     */
    public function push(...$elements): self
    {
        foreach ($elements as $element) {
            $this->items[] = $element;
        }

        return $this;
    }

    /**
     * Returns a new instance that contains the values with reset keys.
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

    /**
     * Dynamically access collection properties.
     *
     * @param  string  $name
     * @return int
     *
     * @throws Exception
     */
    public function __get(string $name)
    {
        if ($name === 'length') {
            return $this->count();
        }
        throw new Exception("Property [$name] does not exist on this collection instance.");
    }

}