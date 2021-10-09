<?php

namespace Rudashi\Contracts;

interface JavaScriptSetInterface
{

    public function add($value = null): self;

    public function clear(): self;

    public function delete($value): bool;

    public function entries(): array;

    public function forEach(callable $callback): self;

    public function has($value): bool;

    public function keys(): array;

    public function values(): array;

}