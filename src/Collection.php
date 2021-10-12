<?php

namespace Rudashi;

use JsonException;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Traits\Enumerable;

class Collection implements EnumeratedInterface, ArrayInterface
{
    use Enumerable;

    protected array $items = [];

    public function __construct(...$items)
    {
        $this->items = $this->getArrayItems(func_num_args() === 1 ? $items[0] : $items);
    }

    public function keys(): self
    {
        return new static(array_keys($this->items));
    }

    public function values(): self
    {
        return new static(array_values($this->items));
    }

    public function map(callable $callback): self
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items) ?: []);
    }

    public function toArray(): array
    {
        return $this->map(function ($value) {
            return $value instanceof ArrayInterface ? $value->toArray() : $value;
        })->all();
    }

    /**
     * @param int $options
     * @return string
     * @throws JsonException
     */
    public function toJson(int $options = JSON_THROW_ON_ERROR): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }

}