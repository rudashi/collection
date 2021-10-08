<?php

namespace Tests;

use Rudashi\Map;
use PHPUnit\Framework\TestCase;

class MapJavaScriptMapTest extends TestCase
{

    public function test_size_property(): void
    {
        $array = ['name' => 'Hello'];

        $map = new Map($array);
        self::assertEquals(1, $map->size);

        $map->push(['second' => 'password']);
        self::assertEquals(2, $map->size);
    }

    public function test_clear(): void
    {
        $map = new Map([
            'bar' => 'baz',
            1 => 'foo'
        ]);

        self::assertEquals(2, $map->size);

        $map->clear();

        self::assertEquals(0, $map->size);

    }

}
