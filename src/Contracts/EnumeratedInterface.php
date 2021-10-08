<?php

namespace Rudashi\Contracts;

interface EnumeratedInterface
{

    public function all(): array;

    public function get($key, $default = null);

    public function set($key, $value): self;

}