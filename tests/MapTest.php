<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Map;

class MapTest extends TestCase
{

    public function test_all(): void
    {
        $array = ['name' => 'Hello', 'epics' => [1, 5.45, 8, 'apple'], new Map([])];

        self::assertEquals($array, (new Map($array))->all());
    }

    public function test_get(): void
    {
        $map = new Map(['first' => 'apple', 'second' => 'banana', 'third' => 'grapes']);

        self::assertEquals('banana', $map->get('second'));
        self::assertNull($map->get('fake'));
        self::assertEquals('something', $map->get('fake', 'something'));
    }

    public function test_set(): void
    {
        $map = new Map(['first' => 'apple', 'third' => 'grapes']);

        self::assertInstanceOf(Map::class, $map->set('second', 'banana'));
        self::assertEquals(['first' => 'apple', 'third' => 'grapes', 'second' => 'banana'], $map->toArray());
    }

    public function test_to_array(): void
    {
        $array = ['name' => 'Hello'];
        $array2 = ['name' => 'Hello', 'map' => new Map(['test' => new Map([])])];

        self::assertEquals($array, (new Map($array))->toArray());
        self::assertEquals(['name' => 'Hello', 'map' => ['test' => []]], (new Map($array2))->toArray());
    }

}
