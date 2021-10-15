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

    public function test_count(): void
    {
        $c = new Collection(['foo', 'bar']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(2, $c->count());
    }

    public function test_countable(): void
    {
        $c = new Collection(['foo', 'bar']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertCount(2, $c);
    }

    public function test_first(): void
    {
        $c = new Collection(['size' => 'XL', 'color' => 'gold']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame('XL', $c->first());
    }

    public function test_firstWhere(): void
    {
        $c = new Collection([
            ['name' => 'John', 'fav_color' => 'green'],
            ['name' => 'Samuel', 'fav_color' => 'blue'],
            ['name' => 'Anna', 'fav_color' => 'green', '_meta' => ['something' => 'information']],
            ['name' => 'Jerry', 'fav_color' => 'blue', '_meta' => ['something' => 'information']],
        ]);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['name' => 'John', 'fav_color' => 'green'], $c->firstWhere('fav_color', 'green'));
        self::assertSame(['name' => 'Samuel', 'fav_color' => 'blue'], $c->firstWhere('name', 'Samuel'));
        self::assertSame(['name' => 'Anna', 'fav_color' => 'green', '_meta' => ['something' => 'information']], $c->firstWhere('_meta.something', 'information'));
        self::assertSame(['name' => 'Anna', 'fav_color' => 'green', '_meta' => ['something' => 'information']], $c->firstWhere(['_meta', 'something'], 'information'));
        self::assertSame('blue', $c->firstWhere('name', 'Samuel')['fav_color']);
        self::assertNull($c->firstWhere('name', 'nothing'));
        self::assertNull($c->firstWhere('wrong_key', 'John'));
    }

    public function test_firstWhere_different_operator(): void
    {
        $c = new Collection([
            ['order' => 1],
            ['order' => 2],
            ['order' => 3],
            ['order' => 4],
            ['order' => 5],
        ]);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame(['order' => 4], $c->firstWhere('order', '>', 3));
        self::assertSame(['order' => 3], $c->firstWhere('order', '>=', 3));
        self::assertSame(['order' => 1], $c->firstWhere('order', '<', 3));
        self::assertSame(['order' => 2], $c->firstWhere('order', '!==', 1));
    }

    public function test_firstWhere_in_collection_of_object(): void
    {
        $obj_1 = (object)['name' => 'John', 'fav_color' => 'green'];
        $obj_2 = (object)['name' => 'Samuel', 'fav_color' => 'blue'];
        $obj_3 = (object)['name' => 'Anna', 'fav_color' => 'green', '_meta' => ['something' => 'information']];

        $c = new Collection([$obj_1, $obj_2, $obj_3]);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame($obj_1, $c->firstWhere('name', 'John'));
        self::assertSame($obj_2, $c->firstWhere('name', 'Samuel'));
        self::assertSame('blue', $c->firstWhere('name', 'Samuel')->fav_color);
        self::assertSame($obj_3, $c->firstWhere('_meta.something', 'information'));
        self::assertNull($c->firstWhere('name', 'nothing'));
        self::assertNull($c->firstWhere('wrong_key', 'John'));
    }

    public function test_first_with_callback(): void
    {
        $c = new Collection(['size' => 'XL', 'color' => 'gold']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame('gold', $c->first(fn($value) => $value === 'gold'));
    }

    public function test_first_with_callback_and_default(): void
    {
        $c = new Collection(['size' => 'XL', 'color' => 'gold']);

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame('default', $c->first(fn($value) => $value === 'silver', 'default'));
    }

    public function test_first_with_default_and_without_callback(): void
    {
        $c = new Collection;

        self::assertInstanceOf(Collection::class, $c);
        self::assertSame('default', $c->first(null, 'default'));
    }

    public function test_isEmpty(): void
    {
        $c_1 = new Collection();
        $c_2 = new Collection(['foo', 'bar']);

        self::assertInstanceOf(Collection::class, $c_1);
        self::assertSame(0, $c_1->count());
        self::assertTrue($c_1->isEmpty());

        self::assertInstanceOf(Collection::class, $c_2);
        self::assertSame(2, $c_2->count());
        self::assertFalse($c_2->isEmpty());
    }

    public function test_isNotEmpty(): void
    {
        $c_1 = new Collection();
        $c_2 = new Collection(['foo', 'bar']);

        self::assertInstanceOf(Collection::class, $c_1);
        self::assertSame(0, $c_1->count());
        self::assertFalse($c_1->isNotEmpty());

        self::assertInstanceOf(Collection::class, $c_2);
        self::assertSame(2, $c_2->count());
        self::assertTrue($c_2->isNotEmpty());
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