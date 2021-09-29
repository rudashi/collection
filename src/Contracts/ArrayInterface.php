<?php

namespace Rudashi\Contracts;

use ArrayAccess;

interface ArrayInterface extends ArrayAccess
{

    public function toArray(): array;

}