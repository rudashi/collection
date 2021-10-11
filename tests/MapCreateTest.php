<?php

namespace Tests;

use Rudashi\Map;
use PHPUnit\Framework\TestCase;
use TypeError;

class MapCreateTest extends TestCase
{

    public function test_constructor(): void
    {
        $string = 'foo';
        $map = new Map($string);

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(1, $map->toArray());
        self::assertEquals([$string], $map->toArray());
    }

    public function test_constructor_empty(): void
    {
        $map = new Map();

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(0, $map->toArray());
        self::assertEquals([], $map->toArray());
    }

    public function test_constructor_multiple_items(): void
    {
        $string = 'foo';
        $array = [];
        $number = 1;
        $map = new Map($string, $array, $number);

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(3, $map->toArray());
        self::assertEquals([$string, $array, $number], $map->toArray());
    }

    public function test_static_from_string(): void
    {
        $string = 'foo';
        $map = Map::from($string);

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(3, $map->toArray());
        self::assertEquals(['f', 'o', 'o'], $map->toArray());
        self::assertEquals([0 => 'f', 1 => 'o', 2 => 'o'], $map->toArray());
    }

    public function test_static_from_empty_string(): void
    {
        $string = '';
        $map = Map::from($string);
        self::assertInstanceOf(Map::class, $map);
        self::assertCount(1, $map->toArray());
        self::assertEquals([''], $map->toArray());
    }

    public function test_static_from_array(): void
    {
        $array = ['foo', 'bar', 'baz', 'foo'];
        $map = Map::from($array);

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(4, $map->toArray());
        self::assertEquals($array, $map->toArray());
    }

    public function test_static_from_map(): void
    {
        $array = ['foo', 'window'];
        $map = Map::from(new Map($array));
        $map_2 = Map::from($map);

        self::assertInstanceOf(Map::class, $map);
        self::assertInstanceOf(Map::class, $map_2);
        self::assertCount(2, $map->toArray());
        self::assertEquals($array, $map->toArray());
        self::assertSame($map, $map_2);
    }

    public function test_static_from_map_nested(): void
    {
        $array = [['1', 'a'], ['2', 'b']];
        $map = Map::from(new Map($array));

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(2, $map->toArray());
        self::assertEquals($array, $map->toArray());
        self::assertEquals($array, Map::from($map->values())->toArray());
        self::assertEquals([0, 1], Map::from($map->keys())->toArray());
    }

    public function test_static_from_range(): void
    {
        $range = 5;
        $map = Map::from($range);

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(5, $map->toArray());
        self::assertEquals([0, 1, 2, 3, 4], $map->toArray());
    }

    public function test_static_from_with_callback(): void
    {
        $array = [1, 2, 3];
        $map = Map::from($array, function($value) {
            return $value + $value;
        });

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(3, $map->toArray());
        self::assertEquals([2, 4, 6], $map->toArray());
    }

    public function test_from_json(): void
    {
        $map = Map::from('["a", "b"]');

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(2, $map->toArray());
        self::assertEquals(['a', 'b'], $map->toArray());
    }

    public function test_from_json_object(): void
    {
        $map = Map::from('{"a": "b"}');

        self::assertInstanceOf(Map::class, $map);
        self::assertEquals(['a' => 'b'], $map->toArray());
    }

    public function test_from_empty_json(): void
    {
        $map = Map::from('""');

        self::assertInstanceOf(Map::class, $map);
        self::assertCount(1, $map->toArray());
        self::assertEquals([''], $map->toArray());
    }

    public function test_failed_static_from(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('NULL is not iterable');

        Map::from();
    }

}
