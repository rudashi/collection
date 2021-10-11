<?php

namespace Rudashi;

use ArrayAccess;
use Closure;
use Exception;
use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Contracts\JavaScriptArrayInterface;
use Rudashi\Contracts\JavaScriptMapInterface;
use Rudashi\Traits\Arrayable;
use Rudashi\Traits\Enumerable;
use TypeError;

/**
 * @property int $length
 * @property int $size
 */
class Map implements JavaScriptArrayInterface, JavaScriptMapInterface, EnumeratedInterface, ArrayInterface, ArrayAccess
{
    use Arrayable,
        Enumerable;

    protected array $items = [];

    public function __construct(...$items)
    {
        $this->items = $this->getArrayItems(func_num_args() === 1 ? $items[0] : $items);
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
     * Returns item at index.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/at
     *
     * @param int $index
     * @return mixed
     */
    public function at(int $index)
    {
        $length = $this->count();

        if ($length === 0 || abs($index) > $length) {
            return null;
        }

        $index = $index < 0 ? $length + $index : $index;

        foreach ($this->items as $key => $value) {
            if ($key === $index) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Removes all elements.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/clear
     *
     * @return static
     */
    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * Merge two or more arrays to new instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/concat
     *
     * @param mixed ...$elements
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
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/size
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Removes the specified element.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/delete
     *
     * @param mixed $key
     * @return bool
     */
    public function delete($key): bool
    {
        if ($this->has($key)) {
            $this->offsetUnset($key);
            return true;
        }
        return false;
    }

    /**
     * Returns a boolean indicating whether an element with the specified key exists or not.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/has
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->offsetExists($key);
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
        $length = $this->count();

        if ($fromIndex > $length) {
            return false;
        }

        if ($fromIndex < 0) {
            $fromIndex = $length + $fromIndex;
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
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/entries
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
     * @param callable $callback
     * @param mixed $default
     * @return mixed
     */
    public function find(callable $callback, $default = null)
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
     * @param callable $callback
     * @return int|string|null
     */
    public function findIndex(callable $callback)
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
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/forEach
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
     * Returns a specified element by a key.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/get
     *
     * @param mixed $key
     * @param callback|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $default instanceof Closure ? $default() : $default;
    }

    /**
     * Returns the first matching index which a given element can be found.
     * If no value found, -1 is returned.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/indexOf
     *
     * @param mixed $searchElement
     * @param int $fromIndex
     * @return int|string
     */
    public function indexOf($searchElement, int $fromIndex = 0)
    {
        $length = $this->count();

        if ($fromIndex > $length || $length === 0) {
            return -1;
        }

        $fromIndex = $fromIndex < 0 ? $length + $fromIndex : $fromIndex;

        foreach ($this->items as $index => $value) {
            if ($index >= $fromIndex && $value === $searchElement) {
                return $index;
            }
        }
        return -1;
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
     * Concatenates the string representation of all elements.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/join
     *
     * @param string $separator
     * @return string
     */
    public function join(string $separator = ','): string
    {
        $items = $this->map(function($item) {
            if ($item instanceof self || (is_array($item) && count($item) > 0)) {
                $item = static::from($item)->flat(INF)->values()->join();
            }
            if (is_bool($item)) {
                return $item ? 'true' : 'false';
            }
            return (is_array($item) && count($item) === 0) || is_null($item) ? '' : $item;
        });

        return $this->implode($separator, $items);
    }

    /**
     * Returns a new instance that contains the keys.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/keys
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/keys
     *
     * @return static
     */
    public function keys(): self
    {
        return new static(array_keys($this->items));
    }

    /**
     * Returns the last matching index which a given element can be found.
     * If no value found, -1 is returned.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/lastIndexOf
     *
     * @param mixed $searchElement
     * @param int|null $fromIndex
     * @return int|string
     */
    public function lastIndexOf($searchElement, int $fromIndex = null)
    {
        $length = $this->count();

        if ($length === 0) {
            return -1;
        }

        $array = $this->items;

        if (null !== $fromIndex && $fromIndex !== -1 && $fromIndex < $length - 1) {
            $array = array_slice($array, 0, -$fromIndex + 1);
        }

        foreach (array_reverse($array, true) as $index => $value) {
            if ($value === $searchElement) {
                return $index;
            }
        }
        return -1;
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
        $keys = array_keys($this->items);
        $elements = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $elements) ?: []);
    }

    /**
     * Creates a new map instance from arguments.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/of
     *
     * @param mixed ...$items
     * @return static
     */
    public static function of(...$items): self
    {
        return new static($items);
    }

    /**
     * Removes the last element and returns that element.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/pop
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Push one or more items onto the end of the collection.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/push
     *
     * @param  mixed ...$elements
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
     * Execute a callback over each item reducing to a single value.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/reduce
     *
     * @param callable $callback
     * @param mixed $initialValue
     * @return mixed
     */
    public function reduce(callable $callback, $initialValue = null)
    {
        return array_reduce($this->items, $callback, $initialValue);
    }

    /**
     * Execute a callback over each item (from right-to-left) reducing to a single value.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/reduceRight
     *
     * @param callable $callback
     * @param mixed $initialValue
     * @return mixed
     */
    public function reduceRight(callable $callback, $initialValue = null)
    {
        return array_reduce(array_reverse($this->items), $callback, $initialValue);
    }

    /**
     * Returns a new instance with the order of the elements reversed.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/reverse
     *
     * @param bool $preserve_keys
     * @return static
     */
    public function reverse(bool $preserve_keys = false): self
    {
        return new static(array_reverse($this->items, $preserve_keys));
    }

    /**
     * Adds or updates an element with a specified key and a value.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/set
     *
     * @param mixed $key
     * @param mixed $value
     * @return static
     */
    public function set($key, $value = null): self
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Removes the first element and returns that element.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/shift
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Returns a new instance with portion of items between $start and $end.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/slice
     *
     * @param int $start
     * @param int|null $end
     * @return static
     */
    public function slice(int $start, int $end = null): self
    {
        if ($start === $end) {
            return new static();
        }

        $length = $this->count() - 1;

        if ($end >= $length) {
            $end = $end === $length ? -1 : null;
        }

        return new static(array_slice($this->items, $start, $end, true));
    }

    /**
     * Method tests whether at least one element passes the test.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/some
     *
     * @param callable $callback
     * @return bool
     */
    public function some(callable $callback): bool
    {
        foreach($this->items as $key => $item) {
            if ($callback($item, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a new sorted instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/sort
     *
     * @param callable|int|null $callback
     * @return static
     */
    public function sort($callback = null): self
    {
        $items = $this->items;

        $callback && is_callable($callback)
            ? usort($items, $callback)
            : sort($items, $callback ?? SORT_REGULAR);

        return new static($items);
    }

    /**
     * Modifies instance and returns a new instance with existing elements removed or replaced.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/splice
     *
     * @param int $start
     * @param int|null $deleteCount
     * @param mixed ...$item
     * @return static
     */
    public function splice(int $start, int $deleteCount = null, ...$item): self
    {
        if ($deleteCount === null) {
            return new static(array_splice($this->items, $start));
        }

        return new static(array_splice($this->items, $start, $deleteCount, $item));
    }

    /**
     * Returns a string representing the instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/toString
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->join();
    }

    /**
     * Adds one or more elements to the beginning and returns the new instance.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/unshift
     *
     * @param mixed ...$item
     * @return static
     */
    public function unshift(...$item): self
    {
        array_unshift($this->items, ...$item);

        return $this;
    }

    /**
     * Returns a new instance that contains the values with reset keys.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/values
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Map/values
     *
     * @return static
     */
    public function values(): self
    {
        return new static(array_values($this->items));
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

    public function __toString(): ?string
    {
        try {
            return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return null;
        }
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
        if ($name === 'length' || $name === 'size') {
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

    private function implode(string $glue, $items): string
    {
        return implode($glue, $items instanceof self ? $items->all() : $items);
    }

}