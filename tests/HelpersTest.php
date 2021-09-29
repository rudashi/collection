<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Map;

class HelpersTest extends TestCase
{

    public function test_map_function_exists(): void
    {
        self::assertTrue(function_exists('map'));
        self::assertInstanceOf( Map::class, map());
        self::assertInstanceOf( Map::class, map([]));
        self::assertInstanceOf( Map::class, map('apple'));
        self::assertInstanceOf( Map::class, map(new Map));
    }

}
