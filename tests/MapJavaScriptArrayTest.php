<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Rudashi\Map;

class MapJavaScriptArrayTest extends TestCase
{

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

    public function test_concat_with_array(): void
    {
        $letters = ['a', 'b', 'c'];
        $numbers = [1, 2, 3];
        $results = (new Map($letters))->concat($numbers);

        self::assertInstanceOf(Map::class, $results);
        self::assertEquals(['a', 'b', 'c', 1, 2, 3], $results->toArray());
    }

    public function test_concat_with_multiple_arrays(): void
    {
        $num1 = [1, 2, 3];
        $num2 = [4, 5, 6];
        $num3 = [7, 8, 9];
        $results = (new Map($num1))->concat($num2, $num3);

        self::assertInstanceOf(Map::class, $results);
        self::assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $results->toArray());
    }

    public function test_concat_different_values(): void
    {
        $letters = ['a', 'b', 'c'];
        $results = (new Map($letters))->concat(1, [2, 3]);

        self::assertInstanceOf(Map::class, $results);
        self::assertEquals(['a', 'b', 'c', 1, 2, 3], $results->toArray());
    }

    public function test_concat_nested_arrays(): void
    {
        $num1 = [[1]];
        $num2 = [2, [3]];
        $results = (new Map($num1))->concat($num2);

        self::assertInstanceOf(Map::class, $results);
        self::assertEquals([[1], 2, [3]], $results->toArray());
    }

    public function test_concat_map(): void
    {
        $first = new Map([1, 2]);
        $second = new Map(['a', 'b']);
        $third = new Map(['x' => 'foo', 'y' => 'bar']);

        $results = $first->concat($second)->concat($third);

        self::assertInstanceOf(Map::class, $results);
        self::assertEquals([1, 2, 'a', 'b', 'foo', 'bar'], $results->toArray());
    }

    public function test_filter(): void
    {
        $map = new Map(['spray', 'limit', 'elite', 'exuberant', 'destruction', 'present']);
        $results = $map->filter(function($value) {
            return strlen($value) > 6;
        });

        self::assertEquals([3 => 'exuberant', 4 => 'destruction', 5 => 'present'], $results->toArray());
    }

    public function test_filter_with_reset_keys(): void
    {
        $map = new Map(['spray', 'limit', 'elite', 'exuberant', 'destruction', 'present']);
        $results = $map->filter(function($value) {
            return strlen($value) > 6;
        }, true);

        self::assertEquals([0 => 'exuberant', 1 => 'destruction', 2 => 'present'], $results->toArray());
    }

    public function test_filter_searching(): void
    {
        $map = new Map(['apple', 'banana', 'grapes', 'mango', 'orange']);
        $first = $map->filter(fn($value) => stripos($value, 'ap') !== false, true);
        $second = $map->filter(fn($value) => stripos($value, 'an') !== false, true);

        self::assertEquals(['apple', 'grapes'], $first->toArray());
        self::assertEquals(['banana', 'mango', 'orange'], $second->toArray());
    }

    public function test_filter_callback_nested(): void
    {
        $map = new Map([['id' => 1, 'name' => 'foo'], ['id' => 2, 'name' => 'bar']]);
        $mapper = $map->filter(fn($item) => $item['id'] === 2, true);

        self::assertEquals([['id' => 2, 'name' => 'bar']], $mapper->toArray());
    }

    public function test_filter_callback_with_key(): void
    {
        $map = new Map(['id' => 1, 'name' => 'foo', 'title' => 'bar']);
        $mapper = $map->filter(fn($item, $key) => $key !== 'id');

        self::assertEquals(['name' => 'foo', 'title' => 'bar'], $mapper->toArray());
    }

    public function test_filter_no_callback(): void
    {
        $map = new Map([1, 'foo', null, 3, false, '', 0, []]);
        $result = $map->filter(null, true);

        self::assertEquals([1, 'foo', 3], $result->toArray());
    }

    public function test_copy_within(): void
    {
        self::assertEquals([1, 2, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(5)->toArray());
        self::assertEquals([1, 2, 1, 2, 3], (new Map([1, 2, 3, 4, 5]))->copyWithin(2)->toArray());
        self::assertEquals([1, 2, 3, 1, 2], (new Map([1, 2, 3, 4, 5]))->copyWithin(-2)->toArray());
        self::assertEquals([4, 5, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(0, 3)->toArray());
        self::assertEquals([4, 2, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(0, 3, 4)->toArray());
        self::assertEquals([1, 2, 3, 3, 4], (new Map([1, 2, 3, 4, 5]))->copyWithin(-2, -3, -1)->toArray());
    }

    public function test_entries(): void
    {
        $map = new Map(['a', 'b', 'c']);

        self::assertEquals([0 => 'a', 1 => 'b', 2 => 'c'], $map->entries());
    }

    public function test_includes(): void
    {
        self::assertTrue((new Map([1, 2, 3]))->includes(2));
        self::assertFalse((new Map([1, 2, 3]))->includes(4));
        self::assertFalse((new Map([1, 2, 3]))->includes(3, 3));
        self::assertTrue((new Map([1, 2, 3]))->includes(3, -1));
        self::assertFalse((new Map(['a', 'b', 'c']))->includes('a', -2));
        self::assertTrue((new Map(['a', 'b', 'c']))->includes('a', -100));
        self::assertTrue((new Map([1, 2, NAN]))->includes(NAN));
        self::assertFalse((new Map(["1", "2", "3"]))->includes(3));
    }

    public function test_every(): void
    {
        $isBigEnough = static function($value) {
            return $value >= 10;
        };

        $isSubset = static function($array1, $array2) {

            return (new Map($array2))->every(function ($element) use ($array1) {
                return (new Map($array1))->includes($element);
            });
        };

        self::assertFalse((new Map([12, 5, 8, 130, 44]))->every($isBigEnough));
        self::assertTrue((new Map([12, 54, 18, 130, 44]))->every($isBigEnough));

        self::assertTrue((new Map([1, 30, 39, 29, 10, 13]))->every(fn($value) => $value < 40));

        self::assertTrue($isSubset([1, 2, 3, 4, 5, 6, 7], [5, 7, 6]));
        self::assertFalse($isSubset([1, 2, 3, 4, 5, 6, 7], [5, 8, 7]));
    }

    public function test_fill(): void
    {
        $map = new Map([1, 2, 3]);

        self::assertEquals([4, 4, 4], $map->fill(4)->toArray());
        self::assertEquals([1, 4, 4], $map->fill(4, 1)->toArray());
        self::assertEquals([1, 4, 3], $map->fill(4, 1, 2)->toArray());
        self::assertEquals([1, 2, 3], $map->fill(4, 1, 1)->toArray());
        self::assertEquals([1, 2, 3], $map->fill(4, 3, 3)->toArray());
        self::assertEquals([4, 2, 3], $map->fill(4, -3, -2)->toArray());
        self::assertEquals([1, 2, 3], $map->fill(4, 3, 5)->toArray());
        self::assertEquals([(object) ['foo' => 'bar'], (object) ['foo' => 'bar'], (object) ['foo' => 'bar']], $map->fill((object) ['foo' => 'bar'])->toArray());
        self::assertEquals([4, 4, 4], Map::from(3)->fill(4)->toArray());
    }

    public function test_fill_matrix(): void
    {
        $map = Map::from(3);

        foreach ($map->all() as $index => $item) {
            $map->set($index, Map::from(4)->fill(1));
        }
        $map->get(0)->set(0, 10);

        self::assertEquals(10, $map->get(0)->get(0));
        self::assertEquals(1, $map->get(1)->get(0));
        self::assertEquals(1, $map->get(2)->get(0));
    }

    public function test_find(): void
    {
        $map = new Map([
            ['name' => 'apples', 'quantity' => 2],
            ['name' => 'bananas', 'quantity' => 0],
            ['name' => 'cherries', 'quantity' => 5]
        ]);

        self::assertEquals(['name' => 'cherries', 'quantity' => 5], $map->find(fn($value) => $value['name'] === 'cherries'));
        self::assertEquals(['name' => 'bananas', 'quantity' => 0], $map->find(fn($value) => $value['quantity'] === 0));
    }

    public function test_find_nothing(): void
    {
        $map = new Map([1, 2, 3, 4]);

        $this->assertNull($map->find(fn($value) => $value === 5));
    }

    public function test_findIndex(): void
    {
        $map = new Map([5, 12, 8, 130, 44]);

        self::assertEquals(3, $map->findIndex(fn($value) => $value > 13));
    }

    public function test_findIndex_nothing(): void
    {
        $map = new Map([1, 2, 3, 4]);

        $this->assertNull($map->findIndex(fn($value) => $value === 5));
    }

}