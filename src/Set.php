<?php

namespace Rudashi;

use Exception;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Contracts\JavaScriptSetInterface;
use Rudashi\Traits\Enumerable;

/**
 * @property int $size
 */
class Set implements ArrayInterface, JavaScriptSetInterface, EnumeratedInterface
{
    use Enumerable;

    protected array $items = [];

    public function __construct(...$items)
    {
        $this->items = $this->getArrayItems(func_num_args() === 1 ? $items[0] : $items);
    }

    /**
     * Adds a new element with a specified value to the end.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/add
     *
     * @param mixed $value
     * @return static
     */
    public function add($value = null): self
    {
        if (!$this->has($value)) {
            $this->items[] = $value;
        }

        return $this;
    }

    /**
     * Removes all elements.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/clear
     *
     * @return static
     */
    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * Removes the specified element.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/delete
     *
     * @param mixed $value
     * @return bool
     */
    public function delete($value): bool
    {
        if ($this->has($value)) {
            unset($this->items[array_search($value, $this->items, true)]);
            return true;
        }
        return false;
    }

    /**
     * Returns an array that contains the value/value pairs.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/entries
     *
     * @return array
     */
    public function entries(): array
    {
        return array_combine(array_values($this->items), $this->items);
    }

    /**
     * Execute a callback over each item.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/forEach
     *
     * @param callable $callback
     * @return static
     */
    public function forEach(callable $callback): self
    {
        foreach ($this->items as $value) {
            if ($callback($value) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Returns a boolean indicating whether an element with the specified value exists or not.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/has
     *
     * @param mixed $value
     * @return bool
     */
    public function has($value): bool
    {
        return in_array($value, $this->items, true);
    }

    /**
     * Returns an array that contains the values.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/keys
     *
     * @return array
     */
    public function keys(): array
    {
        return $this->values();
    }

    /**
     * Returns an array that contains the values.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/values
     *
     * @return array
     */
    public function values(): array
    {
        return $this->items;
    }

    /**
     * Returns the number of elements.
     * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Set/size
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->all());
    }

    public function toArray(): array
    {
        $results = [];

        foreach ($this->all() as $value) {
            $results[] = $value instanceof ArrayInterface ? $value->toArray() : $value;
        }
        return $results;
    }

    protected function getArray($items): array
    {
        return array_values(array_filter($items, static function($v, $k) use ($items) {
            return array_search($v, $items, true) === $k;
        }, ARRAY_FILTER_USE_BOTH));
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
        if ($name === 'size') {
            return $this->count();
        }
        throw new Exception("Property [$name] does not exist on this collection instance.");
    }

}