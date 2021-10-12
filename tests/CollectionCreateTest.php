<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Collection;
use stdClass;

class CollectionCreateTest extends TestCase
{

    public function test_constructor(): void
    {
        $string = 'foo';
        $c = new Collection($string);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c);
        self::assertSame([$string], $c->all());
    }

    public function test_constructor_empty(): void
    {
        $c = new Collection();

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(0, $c);
        self::assertEmpty($c->all());
        self::assertSame([], $c->all());
    }

    public function test_constructor_from_collection(): void
    {
        $array = ['foo', 'window'];
        $c_1 = new Collection($array);
        $c_2 = new Collection($c_1);

        self::assertInstanceOf(Collection::class, $c_1);
        self::assertCount(2, $c_1);
        self::assertSame($array, $c_1->all());

        self::assertInstanceOf(Collection::class, $c_2);
        self::assertCount(2, $c_2);
        self::assertSame($array, $c_2->all());

        self::assertEquals($c_1, $c_2);
    }

    public function test_constructor_from_array(): void
    {
        $array = ['foo' => 'bar', 'baz', 'foo'];
        $c = new Collection($array);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(3, $c);
        self::assertSame($array, $c->all());
    }

    public function test_constructor_from_collection_nested(): void
    {
        $array = ['a' => ['1', 'a'], 'b' => ['2', 'b']];
        $c = new Collection(new Collection($array));

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(2, $c);
        self::assertSame($array, $c->all());
        self::assertSame([0 => ['1', 'a'], 1 => ['2', 'b']], $c->values()->all());
        self::assertSame(['a', 'b'], $c->keys()->all());
    }

    public function test_constructor_from_empty_string(): void
    {
        $string = '';
        $c = new Collection($string);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c);
        self::assertSame([''], $c->all());
    }

    public function test_constructor_from_null(): void
    {
        $c = new Collection(null);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(0, $c);
        self::assertEmpty($c->all());
        self::assertSame([], $c->all());
    }

    public function test_constructor_multiple_items(): void
    {
        $string = 'foo';
        $array = [];
        $number = 1;
        $collect = new Collection();
        $c = new Collection($string, $array, $number, $collect);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(4, $c);
        self::assertSame([$string, $array, $number, $collect], $c->all());
    }

    public function test_constructor_from_empty_json(): void
    {
        $c = new Collection('""');

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c);
        self::assertSame([''], $c->all());
    }

    public function test_constructor_from_json(): void
    {
        $c = new Collection('["a", "b"]');

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(2, $c);
        self::assertSame(['a', 'b'], $c->all());
    }

    public function test_constructor_from_json_object(): void
    {
        $c = new Collection('{"a": "b"}');

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c);
        self::assertSame(['a' => 'b'], $c->all());
    }

    public function test_constructor_from_object(): void
    {
        $object = new stdClass();
        $object->foo = 'bar';
        $c = new Collection($object);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(1, $c);
        self::assertEquals(['foo' => 'bar'], $c->all());
    }

}
