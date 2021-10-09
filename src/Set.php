<?php

namespace Rudashi;

use Exception;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Contracts\JavaScriptSetInterface;
use Rudashi\Traits\Arrayable;
use Rudashi\Traits\Enumerable;

/**
 * @property int $size
 */
class Set implements ArrayInterface, JavaScriptSetInterface, EnumeratedInterface
{
    use Arrayable,
        Enumerable;

    protected array $items = [];

    public function __construct($items = null)
    {
        $this->items = $items
            ? $this->getArray(
                array_values(array_filter($items, static function($v, $k) use ($items) {
                    return array_search($v, $items, true) === $k;
                }, ARRAY_FILTER_USE_BOTH))
            )
            : []
        ;
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