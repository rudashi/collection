<?php

namespace Rudashi;

use Countable;
use Rudashi\Contracts\ArrayInterface;
use Rudashi\Contracts\EnumeratedInterface;
use Rudashi\Traits\Enumerable;

class Collection implements EnumeratedInterface, ArrayInterface, Countable
{
    use Enumerable;

    protected array $items = [];

    public function __construct(...$items)
    {
        $this->items = $this->getArrayItems(func_num_args() === 1 ? $items[0] : $items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function empty(): bool
    {
        return $this->isEmpty();
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function keys(): self
    {
        return new static(array_keys($this->items));
    }

    public function toJson(int $options = JSON_THROW_ON_ERROR): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray(): array
    {
        return $this->map(function ($value) {
            return $value instanceof ArrayInterface ? $value->toArray() : $value;
        })->all();
    }

    public function map(callable $callback): self
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items) ?: []);
    }

    public function values(): self
    {
        return new static(array_values($this->items));
    }

}