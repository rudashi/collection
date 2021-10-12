<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Set;

class MapJavaScriptSetTest extends TestCase
{

    public function test_add(): void
    {
        $set = new Set();

        self::assertInstanceOf(Set::class, $set->add(42));
        self::assertCount(1, $set->all());
        self::assertTrue($set->has(42));

        $set->add(42)->add(42)->add(13);

        self::assertCount(2, $set->all());
        self::assertTrue($set->has(13));
    }

    public function test_clear(): void
    {
        $set = new Set([1, 'foo']);

        self::assertSame(2, $set->size);

        $set->clear();

        self::assertSame(0, $set->size);
    }

    public function test_constructor(): void
    {
        $array = [1, 2, 3, 4, 5];
        $set = new Set($array);

        self::assertInstanceOf(Set::class, $set);
        self::assertCount(5, $set->toArray());
        self::assertSame($array, $set->toArray());
    }

    public function test_constructor_multiple_items(): void
    {
        $string = 'foo';
        $array = [];
        $number = 1;
        $set = new Set($string, $array, $number);

        self::assertInstanceOf(Set::class, $set);
        self::assertCount(3, $set->toArray());
        self::assertEquals([$string, $array, $number], $set->toArray());
    }

    public function test_create_set_with_mixed_values(): void
    {
        $subSet_empty = new Set();
        $subSet = new Set([['x' => 10, 'y' => 20], ['x' => 20, 'y' => 30]]);
        $set = new Set(['1', 1, [], 3, 3, null, $subSet_empty, $subSet]);

        self::assertInstanceOf(Set::class, $set);
        self::assertCount(7, $set->all());
        self::assertSame(['1', 1, [], 3, null, $subSet_empty, $subSet], $set->all());
    }

    public function test_delete(): void
    {
        $set = new Set(['foo']);

        self::assertFalse($set->delete('bar'));
        self::assertTrue($set->delete('foo'));
        self::assertFalse($set->has('foo'));
    }

    public function test_delete_nested(): void
    {
        $set = new Set([(object)['x' => 10, 'y' => 20], (object)['x' => 20, 'y' => 30]]);

        self::assertSame(2, $set->size);

        $set->forEach(function ($value) use ($set) {
            if ($value->x > 10) {
                $set->delete($value);
            }
        });

        self::assertSame(1, $set->size);
    }

    public function test_entries(): void
    {
        $set = new Set([42, 'forty two']);

        self::assertSame([42 => 42, 'forty two' => 'forty two'], $set->entries());
    }

    public function test_forEach(): void
    {
        $array = ['foo', new Set(), null];
        $set = new Set($array);

        $result = [];
        $setter = $set->forEach(function ($value) use (&$result) {
            $result[] = 's[' . ($value instanceof Set ? '[]' : $value) . ']';
        });

        self::assertInstanceOf(Set::class, $setter);
        self::assertSame($array, $setter->all());
        self::assertSame(['s[foo]', 's[[]]', 's[]'], $result);
    }

    public function test_has(): void
    {
        $subSet = new Set();
        $set = new Set([1, 2, 3, 4, 5, $subSet]);

        self::assertTrue($set->has(1));
        self::assertTrue($set->has(5));
        self::assertFalse($set->has('5'));
        self::assertFalse($set->has(6));
        self::assertTrue($set->has($subSet));
        self::assertFalse($set->has(new Set()));
    }

    public function test_keys(): void
    {
        $set = new Set([42, 'forty two']);

        self::assertSame([42, 'forty two'], $set->keys());
    }

    public function test_size_property(): void
    {
        $first = new Set([42, 'forty two', 'forty two', new Set()]);
        $second = new Set([1, 5, 'some text']);

        self::assertSame(3, $first->size);
        self::assertSame(3, $second->size);
    }

    public function test_values(): void
    {
        $set = new Set([42, 'forty two']);

        self::assertSame([42, 'forty two'], $set->values());
    }

}
