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

    public function test_keys(): void
    {
        $array = ['a', 'b', 'c' => 1, null => 'd'];
        $map = new Map($array);
        $keys = $map->keys();

        self::assertInstanceOf(Map::class, $keys);
        self::assertEquals([0, 1, 'c', null], $keys->toArray());
    }

    public function test_values(): void
    {
        $array = ['a', 'b', 'c' => 1, null => 'd'];
        $map = new Map($array);
        $values = $map->values();
        $keys = $values->keys();

        self::assertInstanceOf(Map::class, $values);
        self::assertEquals(['a', 'b', 1, 'd'], $values->toArray());
        self::assertEquals([0, 1, 2, 3], $keys->toArray());
    }

    public function test_map(): void
    {
        $map = (new Map(['first' => 'test', 'not_test']))
            ->map(function($value, $index) {
                return $index.'-'.$value;
            });

        $mapper = (new Map([1, 4, 9, 16]))->map(fn($value) => $value * 2);

        self::assertInstanceOf(Map::class, $map);
        self::assertEquals(['first' => 'first-test', 0 => '0-not_test'], $map->toArray());

        self::assertInstanceOf(Map::class, $mapper);
        self::assertEquals([2, 8, 18, 32], $mapper->toArray());
    }

    public function test_to_array(): void
    {
        $array = ['name' => 'Hello'];
        $array2 = ['name' => 'Hello', 'map' => new Map(['test' => new Map([])])];

        self::assertEquals($array, (new Map($array))->toArray());
        self::assertEquals(['name' => 'Hello', 'map' => ['test' => []]], (new Map($array2))->toArray());
    }

    public function test_length_property(): void
    {
        $array = ['name' => 'Hello'];

        $map = new Map($array);
        self::assertEquals(1, $map->length);

        $map->push(['second' => 'password']);
        self::assertEquals(2, $map->length);
    }

    public function test_count(): void
    {
        $map = new Map(['a', 'b', 'c']);
        self::assertEquals(3, $map->count());

        $map->push('d');
        self::assertEquals(4, $map->count());
    }

    public function test_push(): void
    {
        $map = new Map();
        self::assertEquals(0, $map->count());

        $map->push('a', 'b', 'c', 'd');
        self::assertEquals(4, $map->count());
        self::assertEquals(['a', 'b', 'c', 'd'], $map->toArray());
    }

}
