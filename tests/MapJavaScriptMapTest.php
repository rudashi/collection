<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Map;

class MapJavaScriptMapTest extends TestCase
{

    public function test_clear(): void
    {
        $map = new Map(['bar' => 'baz', 1 => 'foo']);

        self::assertSame(2, $map->size);

        $map->clear();

        self::assertSame(0, $map->size);
    }

    public function test_delete(): void
    {
        $map = new Map(['bar' => 'foo', 1 => 'foo']);

        self::assertTrue($map->delete('bar'));
        self::assertFalse($map->delete('bar'));
        self::assertFalse($map->delete('baz'));
    }

    public function test_entries(): void
    {
        $array = ['bar' => 'foo', 1 => 'foo'];
        $map = new Map($array);

        self::assertSame($array, $map->entries());
    }

    public function test_forEach(): void
    {
        $array = ['foo' => 3, 'bar' => new Map(), 'baz' => null];
        $map = new Map($array);

        $result = [];
        $mapper = $map->forEach(function ($value, $key) use (&$result) {
            $result[] = $key . ($value instanceof Map ? '[]' : $value);
        });

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame($array, $mapper->all());
        self::assertSame(['foo3', 'bar[]', 'baz'], $result);
    }

    public function test_get(): void
    {
        $map = new Map(['bar' => 'foo', 1 => 'foo']);

        self::assertSame('foo', $map->get('bar'));
        self::assertNull($map->get('baz'));
    }

    public function test_has(): void
    {
        $map = new Map(['bar' => 'baz', 1 => 'foo']);

        self::assertTrue($map->has('bar'));
        self::assertFalse($map->has('baz'));
    }

    public function test_keys(): void
    {
        $array = ['0' => 'foo', 1 => 'bar', null => 'baz'];
        $map = new Map($array);
        $keys = $map->keys();

        self::assertInstanceOf(Map::class, $keys);
        self::assertSame([0, 1, ''], $keys->all());
    }

    public function test_set(): void
    {
        $map = new Map();

        self::assertInstanceOf(Map::class, $map->set('bar', 'foo'));
        self::assertTrue($map->has('bar'));
        self::assertSame('foo', $map->get('bar'));

        $map->set('bar', 'baz');

        self::assertTrue($map->has('bar'));
        self::assertSame('baz', $map->get('bar'));
    }

    public function test_size_property(): void
    {
        $array = ['name' => 'Hello'];

        $map = new Map($array);
        self::assertSame(1, $map->size);

        $map->push(['second' => 'password']);
        self::assertSame(2, $map->size);
    }

    public function test_values(): void
    {
        $map = new Map([0 => 'foo', 1 => 'bar']);
        $values = $map->values();

        self::assertInstanceOf(Map::class, $values);
        self::assertSame(['foo', 'bar'], $values->toArray());
    }

}
