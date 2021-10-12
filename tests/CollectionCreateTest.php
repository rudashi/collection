<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Collection;

class CollectionCreateTest extends TestCase
{

    public function test_create(): void
    {
        $string = 'foo';
        $c = new Collection($string);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c->all());
        self::assertSame([$string], $c->all());
    }

    public function test_create_empty(): void
    {
        $c = new Collection();

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(0, $c->all());
        self::assertSame([], $c->all());
    }

    public function test_create_from_Collection(): void
    {
        $array = ['foo', 'window'];
        $c = collect(new Collection($array));
        $c_2 = collect($c);

        self::assertInstanceOf(Collection::class, $c);
        self::assertInstanceOf(Collection::class, $c_2);
        self::assertCount(2, $c->all());
        self::assertSame($array, $c->all());
        self::assertEquals($c, $c_2);
    }

    public function test_create_from_array(): void
    {
        $array = ['foo', 'bar', 'baz', 'foo'];
        $c = collect($array);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(4, $c->all());
        self::assertSame($array, $c->all());
    }

    public function test_create_from_collection_nested(): void
    {
        $array = ['a' => ['1', 'a'], 'b' => ['2', 'b']];
        $c = collect(new Collection($array));

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(2, $c->all());
        self::assertSame($array, $c->all());
        self::assertSame([0 => ['1', 'a'], 1 => ['2', 'b']], $c->values()->all());
        self::assertSame(['a', 'b'], $c->keys()->all());
    }

    public function test_create_from_empty_string(): void
    {
        $string = '';
        $c = collect($string);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c->all());
        self::assertSame([''], $c->all());
    }

    public function test_create_from_null(): void
    {
        $c = new Collection(null);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(0, $c->all());
        self::assertSame([], $c->all());
    }

    public function test_create_multiple_items(): void
    {
        $string = 'foo';
        $array = [];
        $number = 1;
        $collect = collect();
        $c = new Collection($string, $array, $number, $collect);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(4, $c->all());
        self::assertSame([$string, $array, $number, $collect], $c->all());
    }

    public function test_from_empty_json(): void
    {
        $c = collect('""');

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c->all());
        self::assertSame([''], $c->all());
    }

    public function test_from_json(): void
    {
        $c = collect('["a", "b"]');

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(2, $c->all());
        self::assertSame(['a', 'b'], $c->all());
    }

    public function test_from_json_object(): void
    {
        $c = collect('{"a": "b"}');

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['a' => 'b'], $c->all());
    }

}
