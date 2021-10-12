<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Collection;

class CollectionTest extends TestCase
{

    public function test_all(): void
    {
        $array = ['first' => '111', 'second' => [0, 1, true], 'third' => new Collection()];
        $c = new Collection($array);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame($array, $c->all());
    }

    public function test_keys(): void
    {
        $c = new Collection(['first' => '111', 'second' => '222', 'third' => '333']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['first', 'second', 'third'], $c->keys()->all());
    }

    public function test_map(): void
    {
        $c = new Collection(['green' => 'avocado', 'red' => 'apple']);
        $c = $c->map(function ($item, $key) {
            return $key . '-' . $item;
        });

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['green' => 'green-avocado', 'red' => 'red-apple'], $c->all());
    }

    public function test_toArray(): void
    {
        $c = new Collection(['a' => 'Apple', 'b' => 'Banana', new Collection(['foo']), true]);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['a' => 'Apple', 'b' => 'Banana', ['foo'], true], $c->toArray());
    }

    public function test_toJson(): void
    {
        $c = new Collection(['foo' => 'bar']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertJsonStringEqualsJsonString('{"foo":"bar"}', $c->toJson());
    }

    public function test_toJson_with_options(): void
    {
        $c = new Collection(['foo', 'bar']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertJsonStringEqualsJsonString('{"0":"foo","1":"bar"}', $c->toJson(JSON_FORCE_OBJECT));
    }

    public function test_values(): void
    {
        $c = new Collection(['size' => 'XL', 'color' => 'gold']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['XL', 'gold'], $c->values()->all());
    }

    public function test_values_reset_indexes(): void
    {
        $c = new Collection([3 => 11, 1 => 22, 2 => 33, 0 => 44]);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame([0 => 11, 1 => 22, 2 => 33, 3 => 44], $c->values()->all());
    }

}