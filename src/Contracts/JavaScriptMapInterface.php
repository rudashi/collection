<?php

namespace Rudashi\Contracts;

interface JavaScriptMapInterface
{

    public function clear(): self;

    public function delete($key): bool;

    public function entries(): array;

    public function forEach(callable $callback): self;

    public function get($key, $default = null);

    public function has($key): bool;

    public function keys(): self;

    public function set($key, $value = null): self;

    public function values(): self;

}