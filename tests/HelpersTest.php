<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Set;
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

    public function test_set_function_exists(): void
    {
        self::assertTrue(function_exists('set'));
        self::assertInstanceOf( Set::class, set());
        self::assertInstanceOf( Set::class, set([]));
        self::assertInstanceOf( Set::class, set('apple'));
        self::assertInstanceOf( Set::class, set(new Set));
    }

}
