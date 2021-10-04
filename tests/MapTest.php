<?php

namespace Tests;

use Rudashi\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{

    public function test_all(): void
    {
        $array = ['name' => 'Hello', 'epics' => [1, 5.45, 8, 'apple'], new Map([])];

        self::assertEquals($array, (new Map($array))->all());
    }

    public function test_to_array(): void
    {
        $array = ['name' => 'Hello'];
        $array2 = ['name' => 'Hello', 'map' => new Map(['test' => new Map([])])];

        self::assertEquals($array, (new Map($array))->toArray());
        self::assertEquals(['name' => 'Hello', 'map' => ['test' => []]], (new Map($array2))->toArray());
    }

}
