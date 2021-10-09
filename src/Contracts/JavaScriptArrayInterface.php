<?php

namespace Rudashi\Contracts;

interface JavaScriptArrayInterface
{

    public function at(int $index);

    public function concat(...$elements): self;

    public function copyWithin(int $target, int $start = 0, int $end = null): self;

    public function entries(): array;

    public function every(callable $callback): bool;

    public function fill($value, int $start = null, int $end = null): self;

    public function filter(callable $callback = null, bool $reset_keys = false): self;

    public function find(callable $callback, $default = null);

    public function findIndex(callable $callback);

    public function flat($depth = 1): self;

    public function flatMap(callable $callback): self;

    public function forEach(callable $callback): self;

    public static function from($items = null, callable $callback = null): self;

    public function includes($element, int $fromIndex = 0): bool;

    public function indexOf($searchElement, int $fromIndex = 0);

    public static function isArray($items): bool;

    public function join(string $separator = ','): string;

    public function keys(): self;

    public function lastIndexOf($searchElement, int $fromIndex = null);

    public function map(callable $callback): self;

    public static function of(...$items): self;

    public function pop();

    public function push(...$elements): self;

    public function reduce(callable $callback, $initialValue = null);

    public function reduceRight(callable $callback, $initialValue = null);

    public function reverse(bool $preserve_keys = false): self;

    public function shift();

    public function slice(int $start, int $end = null): self;

    public function some(callable $callback): bool;

    public function sort($callback = null): self;

    public function splice(int $start, int $deleteCount = null, ...$item): self;

    public function toString(): string;

    public function unshift(...$item): self;

    public function values(): self;

}