<?php

namespace Rudashi;

use Closure;
use Exception;
use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Contracts\JavaScriptArrayInterface;
use Rudashi\Traits\Arrayable;
use Traversable;
use TypeError;

/**
 * @property int $length
 */
class Map implements JavaScriptArrayInterface, EnumeratedInterface, ArrayInterface
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

    /**
     * Returns a new instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/from
     *
     * @param mixed $items
     * @param callable|null $callback
     * @return static
     */
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
            return $callback ? (new static($items))->map($callback) : new static($items);
        }
        if ($items instanceof self) {
            return $callback ? $items->map($callback) : $items;
        }
        throw new TypeError(gettype($items).' is not iterable');
    }

    /**
     * Merge two or more arrays to new instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/concat
     *
     * @param mixed $elements
     * @return static
     */
    public function concat(...$elements): self
    {
        $result = new static($this);

        foreach ($elements as $args) {
            if ($args instanceof self) {
                foreach ($args->all() as $item) {
                    $result->push($item);
                }
            } else {
                foreach ((array) $args as $item) {
                    $result->push($item);
                }
            }
        }
        return $result;
    }

    /**
     * Copies part of an array to another location in the same array and returns it without modifying its length.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/copyWithin
     *
     * @param int $target
     * @param int $start
     * @param int|null $end
     * @return static
     */
    public function copyWithin(int $target, int $start = 0, int $end = null): self
    {
        $count = $this->count();
        $length = $end ? abs($start - $end) : $count;

        if ($start === 0 && ($target === 0 || $target >= $count)) {
            return $this;
        }

        $items = array_slice($this->items, $start, $length);
        array_splice($this->items, $target, count($items), $items);
        $this->items = array_slice($this->items, 0, $count);

        return $this;
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
     * Determines whether it contains the given element.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/includes
     *
     * @param mixed $element
     * @param int $fromIndex
     * @return bool
     */
    public function includes($element, int $fromIndex = 0): bool
    {
        if ($fromIndex > $this->count()) {
            return false;
        }

        if ($fromIndex < 0) {
            $fromIndex = $this->count() + $fromIndex;
        }

        if (is_array($element)) {
            foreach ($element as $item) {
                if ($this->includes($item, $fromIndex)) {
                    return false;
                }
            }
            return true;
        }

        $items = $fromIndex > 0 ? $this->filter(function ($value, $index) use ($fromIndex) {
            return $index >= $fromIndex;
        }, true) : $this;

        if (is_float($element) && is_nan($element)) {
            return $items->filter(function($value) {
                return is_nan($value);
            })->count() > 0;
        }

        return in_array($element, $items->all(), true);
    }

    /**
     * Returns an array that contains the key/value pairs.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/entries
     *
     * @return array
     */
    public function entries(): array
    {
        return $this->toArray();
    }

    /**
     * Determine if all items pass the test implemented by callback test.
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/every
     * @param  callable  $callback
     * @return bool
     */
    public function every(callable $callback): bool
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns a new instance with changes all items, from a start index to an end index.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/fill
     *
     * @param mixed $value
     * @param int|null $start
     * @param int|null $end
     * @return static
     */
    public function fill($value, int $start = null, int $end = null): self
    {
        $result = new static($this);
        $count = $result->count();

        $start >>= 0;
        $end = (is_int($end) === false) ? $count : $end >> 0;

        $relStart = $start < 0 ? max($count + $start, 0) : min($start, $count);
        $relEnd =  $end < 0 ? max($count + $end, 0) : min($end, $count);

        for ($i = $relStart; $i < $relEnd; $i++) {
            $result->set($i, $value);
        }
        return $result;
    }

    /**
     * Returns a new instance with all elements that pass the test implemented by the provided callback.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/filter
     *
     * @param callable|null $callback
     * @param bool $reset_keys
     * @return static
     */
    public function filter(callable $callback = null, bool $reset_keys = false): self
    {
        if ($callback) {
            $items = array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH);
        } else {
            $items = array_filter($this->items);
        }
        return new static($reset_keys ? array_values($items) : $items);
    }

    /**
     * Returns the first matching element where the callback returns TRUE.
     * If no values satisfy the testing function, $default is returned.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/find
     *
     * @param Closure $callback
     * @param mixed $default
     * @return mixed
     */
    public function find(Closure $callback, $default = null)
    {
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Returns index the first matching element where the callback returns TRUE.
     * If no values satisfy the testing function, null is returned.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/findIndex
     *
     * @param Closure $callback
     * @return int|string|null
     */
    public function findIndex(Closure $callback)
    {
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Returns a new instance with all sub elements concatenated into it recursively up to the specified depth.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/flat
     *
     * @param float|int $depth
     * @return static
     */
    public function flat($depth = 1): self
    {
        return new static($this->flatten($this->items, $depth));
    }

    /**
     * Returns a new instance formed by applying a given callback function to each element and then flattening the result by one level.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/flatMap
     *
     * @param callable $callback
     * @return static
     */
    public function flatMap(callable $callback): self
    {
        return $this->map($callback)->flat();
    }

    /**
     * Execute a callback over each item.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach
     *
     * @param callable $callback
     * @return static
     */
    public function forEach(callable $callback): self
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Determines whether the passed value is an Array.
     * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/isArray
     *
     * @param mixed $items
     * @return bool
     */
    public static function isArray($items): bool
    {
        return is_array($items);
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

    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        return $default instanceof Closure ? $default() : $default;
    }

    public function set($key, $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Determines whether the passed value is a Map.
     *
     * @param mixed $items
     * @return bool
     */
    public static function isMap($items): bool
    {
        return $items instanceOf self;
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

    private function flatten(iterable $items, $depth = INF): array
    {
        $result = [];

        foreach($items as $item) {
            $item = $item instanceof EnumeratedInterface ? $item->all() : $item;

            if(is_iterable($item) && $depth > 0) {
                foreach ($this->flatten($item, $depth - 1) as $value) {
                    $result[] = $value;
                }
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

}