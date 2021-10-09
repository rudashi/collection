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
        self::assertSame([0, 1, 'c', ''], $keys->toArray());
    }

    public function test_values(): void
    {
        $array = ['a', 'b', 'c' => 1, null => 'd'];
        $map = new Map($array);
        $values = $map->values();
        $keys = $values->keys();

        self::assertInstanceOf(Map::class, $values);
        self::assertSame(['a', 'b', 1, 'd'], $values->toArray());
        self::assertSame([0, 1, 2, 3], $keys->toArray());
    }

    public function test_map(): void
    {
        $map = (new Map(['first' => 'test', 'not_test']))
            ->map(function($value, $index) {
                return $index.'-'.$value;
            });

        $mapper = (new Map([1, 4, 9, 16]))->map(fn($value) => $value * 2);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(['first' => 'first-test', 0 => '0-not_test'], $map->toArray());

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame([2, 8, 18, 32], $mapper->toArray());
    }

    public function test_length_property(): void
    {
        $array = ['name' => 'Hello'];

        $map = new Map($array);
        self::assertSame(1, $map->length);

        $map->push(['second' => 'password']);
        self::assertSame(2, $map->length);
    }

    public function test_count(): void
    {
        $map = new Map(['a', 'b', 'c']);
        self::assertSame(3, $map->count());

        $map->push('d');
        self::assertSame(4, $map->count());
    }

    public function test_push(): void
    {
        $map = new Map();
        self::assertSame(0, $map->count());

        $map->push('a', 'b', 'c', 'd');

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(4, $map->count());
        self::assertSame(['a', 'b', 'c', 'd'], $map->toArray());
    }

    public function test_concat_with_array(): void
    {
        $letters = ['a', 'b', 'c'];
        $numbers = [1, 2, 3];
        $results = (new Map($letters))->concat($numbers);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame(['a', 'b', 'c', 1, 2, 3], $results->toArray());
    }

    public function test_concat_with_multiple_arrays(): void
    {
        $num1 = [1, 2, 3];
        $num2 = [4, 5, 6];
        $num3 = [7, 8, 9];
        $results = (new Map($num1))->concat($num2, $num3);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], $results->toArray());
    }

    public function test_concat_different_values(): void
    {
        $letters = ['a', 'b', 'c'];
        $results = (new Map($letters))->concat(1, [2, 3]);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame(['a', 'b', 'c', 1, 2, 3], $results->toArray());
    }

    public function test_concat_nested_arrays(): void
    {
        $num1 = [[1]];
        $num2 = [2, [3]];
        $results = (new Map($num1))->concat($num2);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame([[1], 2, [3]], $results->toArray());
    }

    public function test_concat_map(): void
    {
        $first = new Map([1, 2]);
        $second = new Map(['a', 'b']);
        $third = new Map(['x' => 'foo', 'y' => 'bar']);

        $results = $first->concat($second)->concat($third);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame([1, 2, 'a', 'b', 'foo', 'bar'], $results->toArray());
    }

    public function test_filter(): void
    {
        $map = new Map(['spray', 'limit', 'elite', 'exuberant', 'destruction', 'present']);
        $results = $map->filter(function($value) {
            return strlen($value) > 6;
        });

        self::assertInstanceOf(Map::class, $results);
        self::assertSame([3 => 'exuberant', 4 => 'destruction', 5 => 'present'], $results->toArray());
    }

    public function test_filter_with_reset_keys(): void
    {
        $map = new Map(['spray', 'limit', 'elite', 'exuberant', 'destruction', 'present']);
        $results = $map->filter(function($value) {
            return strlen($value) > 6;
        }, true);

        self::assertInstanceOf(Map::class, $results);
        self::assertSame([0 => 'exuberant', 1 => 'destruction', 2 => 'present'], $results->toArray());
    }

    public function test_filter_searching(): void
    {
        $map = new Map(['apple', 'banana', 'grapes', 'mango', 'orange']);
        $first = $map->filter(fn($value) => stripos($value, 'ap') !== false, true);
        $second = $map->filter(fn($value) => stripos($value, 'an') !== false, true);

        self::assertInstanceOf(Map::class, $first);
        self::assertInstanceOf(Map::class, $second);
        self::assertSame(['apple', 'grapes'], $first->toArray());
        self::assertSame(['banana', 'mango', 'orange'], $second->toArray());
    }

    public function test_filter_callback_nested(): void
    {
        $map = new Map([['id' => 1, 'name' => 'foo'], ['id' => 2, 'name' => 'bar']]);
        $mapper = $map->filter(fn($item) => $item['id'] === 2, true);

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame([['id' => 2, 'name' => 'bar']], $mapper->toArray());
    }

    public function test_filter_callback_with_key(): void
    {
        $map = new Map(['id' => 1, 'name' => 'foo', 'title' => 'bar']);
        $mapper = $map->filter(fn($item, $key) => $key !== 'id');

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame(['name' => 'foo', 'title' => 'bar'], $mapper->toArray());
    }

    public function test_filter_no_callback(): void
    {
        $map = new Map([1, 'foo', null, 3, false, '', 0, []]);
        $mapper = $map->filter(null, true);

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame([1, 'foo', 3], $mapper->toArray());
    }

    public function test_copy_within(): void
    {
        self::assertSame([1, 2, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(5)->toArray());
        self::assertSame([1, 2, 1, 2, 3], (new Map([1, 2, 3, 4, 5]))->copyWithin(2)->toArray());
        self::assertSame([1, 2, 3, 1, 2], (new Map([1, 2, 3, 4, 5]))->copyWithin(-2)->toArray());
        self::assertSame([4, 5, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(0, 3)->toArray());
        self::assertSame([4, 2, 3, 4, 5], (new Map([1, 2, 3, 4, 5]))->copyWithin(0, 3, 4)->toArray());
        self::assertSame([1, 2, 3, 3, 4], (new Map([1, 2, 3, 4, 5]))->copyWithin(-2, -3, -1)->toArray());
    }

    public function test_entries(): void
    {
        $map = new Map(['a', 'b', 'c']);

        self::assertSame([0 => 'a', 1 => 'b', 2 => 'c'], $map->entries());
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

        self::assertSame([4, 4, 4], $map->fill(4)->toArray());
        self::assertSame([1, 4, 4], $map->fill(4, 1)->toArray());
        self::assertSame([1, 4, 3], $map->fill(4, 1, 2)->toArray());
        self::assertSame([1, 2, 3], $map->fill(4, 1, 1)->toArray());
        self::assertSame([1, 2, 3], $map->fill(4, 3, 3)->toArray());
        self::assertSame([4, 2, 3], $map->fill(4, -3, -2)->toArray());
        self::assertSame([1, 2, 3], $map->fill(4, 3, 5)->toArray());
        self::assertEquals([(object) ['foo' => 'bar'], (object) ['foo' => 'bar'], (object) ['foo' => 'bar']], $map->fill((object) ['foo' => 'bar'])->toArray());
        self::assertSame([4, 4, 4], Map::from(3)->fill(4)->toArray());
    }

    public function test_fill_matrix(): void
    {
        $map = Map::from(3);

        foreach ($map->all() as $index => $item) {
            $map->set($index, Map::from(4)->fill(1));
        }
        $map->get(0)->set(0, 10);

        self::assertSame(10, $map->get(0)->get(0));
        self::assertSame(1, $map->get(1)->get(0));
        self::assertSame(1, $map->get(2)->get(0));
    }

    public function test_find(): void
    {
        $map = new Map([
            ['name' => 'apples', 'quantity' => 2],
            ['name' => 'bananas', 'quantity' => 0],
            ['name' => 'cherries', 'quantity' => 5]
        ]);

        self::assertSame(['name' => 'cherries', 'quantity' => 5], $map->find(fn($value) => $value['name'] === 'cherries'));
        self::assertSame(['name' => 'bananas', 'quantity' => 0], $map->find(fn($value) => $value['quantity'] === 0));
    }

    public function test_find_nothing(): void
    {
        $map = new Map([1, 2, 3, 4]);

        self::assertNull($map->find(fn($value) => $value === 5));
    }

    public function test_findIndex(): void
    {
        $map = new Map([5, 12, 8, 130, 44]);

        self::assertSame(3, $map->findIndex(fn($value) => $value > 13));
    }

    public function test_findIndex_nothing(): void
    {
        $map = new Map([1, 2, 3, 4]);

        self::assertNull($map->findIndex(fn($value) => $value === 5));
    }

    public function test_flat(): void
    {
        $map = new Map([1, 2, [3, 4]]);

        self::assertSame([1, 2, 3, 4], $map->flat()->toArray());
    }

    public function test_flat_infinite(): void
    {
        $map = new Map([1, 2, [3, 4, [5, 6, [7, 8, [9, 10]]]]]);

        self::assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $map->flat(INF)->toArray());
    }

    public function test_flat_none(): void
    {
        $map = new Map([0, 1, 2, [[[3, 4]]]]);

        self::assertSame([0, 1, 2, [[[3, 4]]]], $map->flat(0)->toArray());
    }

    public function test_flat_recursive(): void
    {
        $map = new Map([1, 2, [3, 4, [[5, 6]]]]);

        self::assertSame([1, 2, 3, 4, [5, 6]], $map->flat(2)->toArray());
    }

    public function test_flat_traversable(): void
    {
        $map = new Map([[1, 2], new Map([3, 4, [5, 6]])]);

        self::assertSame([1, 2, 3, 4, [5, 6]], $map->flat()->toArray());
    }

    public function test_flat_mixed(): void
    {
        $map = new Map([['Wind', 'Water', ['Fire', null]], [1, 'a', true]]);

        self::assertSame(['Wind', 'Water', 'Fire', null, 1, 'a', true], $map->flat(INF)->toArray());
    }

    public function test_flatMap(): void
    {
        $map = new Map([1, 2, 3, 4]);

        self::assertSame([1, 2, 2, 4, 3, 6, 4, 8], $map->flatMap(fn($x) => [$x, $x * 2])->toArray());
        self::assertSame([[2], [4], [6], [8]], $map->flatMap(fn($x) => [[$x * 2]])->toArray());
    }

    public function test_flatMap_with_function(): void
    {
        $map = new Map(['it`s Sunny in', '', 'California']);
        $mapper = $map->flatMap(fn($x) => explode(' ', $x));
        
        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame(['it`s', 'Sunny', 'in', '', 'California'], $mapper->toArray());
    }

    public function test_flatMap_mutation(): void
    {
        $map = new Map([5, 4, -3, 20, 17, -33, -4, 18]);
        $mapper = $map->flatMap(function($n) {
            if ($n < 0) {
                return [];
            }
            return ($n % 2 === 0) ? [$n] : [$n-1, 1];
        });

        self::assertInstanceOf(Map::class, $mapper);
        self::assertSame([4, 1, 4, 20, 16, 1, 18], $mapper->toArray());
    }

    public function test_forEach(): void
    {
        $map = new Map([2, 5, 9]);

        $result = [];
        $mapper = $map->forEach(function($element, $index) use (&$result) {
            $result[$index] = $element * 2;
        });

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([2, 5, 9], $mapper->toArray());
        self::assertSame([4, 10, 18], $result);
    }

    public function test_forEach_with_break(): void
    {
        $map = new Map([2, 5, '', 9]);

        $result = [];
        $mapper = $map->forEach(function($element, $index) use (&$result) {
            if (is_string($element)) {
                return false;
            }
            $result[$index] = $element * 2;
            return true;
        });

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([2, 5, '', 9], $mapper->toArray());
        self::assertSame([4, 10], $result);
    }

    public function test_isArray(): void
    {
        self::assertTrue(Map::isArray([1, 2, 3]));
        self::assertFalse(Map::isArray(new Map([1, 2, 3])));
        self::assertFalse(Map::isArray('foobar'));
        self::assertFalse(Map::isArray(null));
        self::assertFalse(Map::isArray((object) []));
    }

    public function test_isMap(): void
    {
        self::assertFalse(Map::isMap([1, 2, 3]));
        self::assertTrue(Map::isMap(new Map([1, 2, 3])));
        self::assertFalse(Map::isMap('foobar'));
        self::assertFalse(Map::isMap(null));
        self::assertFalse(Map::isMap((object) []));
    }

    public function test_of(): void
    {
        $map = Map::of('a', 'b', ['c' => 1, null => 'd'], null);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(['a', 'b', ['c' => 1, null => 'd'], null], $map->toArray());
    }

    public function test_indexOf(): void
    {
        $map = new Map(['ant', 'bison', 'camel', 'duck', 'bison']);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(1, $map->indexOf('bison'));
        self::assertSame(4, $map->indexOf('bison', 2));
        self::assertSame(-1, $map->indexOf('bison', 5));
        self::assertSame(1, $map->indexOf('bison', -4));
        self::assertSame(4, $map->indexOf('bison', -3));
        self::assertSame(-1, $map->indexOf('giraffe'));
    }

    public function test_indexOf_on_multidimensional_array(): void
    {
        $map = new Map(['ant', 'bison', 'pet' => 'camel', 'duck', 'bison']);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(1, $map->indexOf('bison'));
        self::assertSame('pet', $map->indexOf('camel'));
        self::assertSame(2, $map->indexOf('duck'));
    }

    public function test_join(): void
    {
        $empty = new Map();
        $one = new Map(['Wind']);
        $map = new Map(['Wind', 'Water', 'Fire']);

        self::assertSame('', $empty->join());
        self::assertSame('Wind', $one->join());
        self::assertSame('Wind,Water,Fire', $map->join());
        self::assertSame('Wind, Water, Fire', $map->join(', '));
        self::assertSame('Wind + Water + Fire', $map->join(' + '));
        self::assertSame('WindWaterFire', $map->join(''));
    }

    public function test_join_null_and_empty_arrays(): void
    {
        $map = new Map(['Fire', [], 'Water', null]);

        self::assertSame('Fire,,Water,', $map->join());
    }

    public function test_join_nested_arrays(): void
    {
        $map = new Map([['Wind', 'Water', ['Fire', null]], [1, 'a', true]]);

        self::assertSame('Wind,Water,Fire,,1,a,true', $map->join());
        self::assertSame('Wind,Water,Fire,-1,a,true', $map->join('-'));
    }

    public function test_join_maps(): void
    {
        $map = new Map([new Map(['Wind', 'Water', 'Fire']), new Map([1, 'a', true])]);

        self::assertSame('Wind,Water,Fire,1,a,true', $map->join());
        self::assertSame('Wind,Water,Fire-1,a,true', $map->join('-'));
    }

    public function test_reverse(): void
    {
        $map = new Map(['one', 'two', 'three']);
        $reversed = $map->reverse();

        self::assertInstanceOf(Map::class, $reversed);
        self::assertSame(['three', 'two', 'one'], $reversed->toArray());
    }

    public function test_reverse_with_keys(): void
    {
        $map = new Map(['one' => 1, 'two' => 2, 'three' => 3]);
        $reversed = $map->reverse(true);

        self::assertInstanceOf(Map::class, $reversed);
        self::assertSame(['three' => 3, 'two' => 2, 'one' => 1], $reversed->toArray());
    }

    public function test_lastIndexOf(): void
    {
        $map = new Map([2, 5, 9, 2]);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(3, $map->lastIndexOf(2));
        self::assertSame(-1, $map->lastIndexOf(7));
        self::assertSame(3, $map->lastIndexOf(2, 3));
        self::assertSame(0, $map->lastIndexOf(2, 2));
        self::assertSame(0, $map->lastIndexOf(2, -2));
        self::assertSame(3, $map->lastIndexOf(2, -1));
    }

    public function test_lastIndexOf_on_multidimensional_array(): void
    {
        $map = new Map(['ant', 'bison', 'pet' => 'camel', 'duck', 'bison']);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(3, $map->lastIndexOf('bison'));
        self::assertSame('pet', $map->lastIndexOf('camel'));
        self::assertSame(2, $map->lastIndexOf('duck'));
    }

    public function test_pop(): void
    {
        $map = new Map(['angel', 'clown', 'mandarin', 'sturgeon']);
        $popped = $map->pop();

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(['angel', 'clown', 'mandarin'], $map->toArray());
        self::assertSame('sturgeon', $popped);
    }

    public function test_reduce(): void
    {
        $map = new Map([0, 1, 2, 3]);
        $sum = $map->reduce(function($previousValue, $currentValue) {
            return $previousValue + $currentValue;
        }, 0);

        self::assertInstanceOf(Map::class, $map);
        self::assertIsInt($sum);
        self::assertSame(6, $sum);
    }

    public function test_reduce_flatten(): void
    {
        $map = new Map([new Map([0, 1]), new Map([2, 3]), new Map([4, 5])]);
        $flattened = $map->reduce(function(Map $previousValue, Map $currentValue) {
            return $previousValue->concat($currentValue);
        }, new Map());

        self::assertInstanceOf(Map::class, $flattened);
        self::assertSame([0, 1, 2, 3, 4, 5], $flattened->toArray());
    }

    public function test_reduce_counting_instances_of_values(): void
    {
        $map = new Map(['Alice', 'Bob', 'Tiff', 'Bruce', 'Alice']);
        $countedNames = $map->reduce(function($allNames, $name) {
            if (isset($allNames[$name])) {
                $allNames[$name]++;
            } else {
                $allNames[$name] = 1;
            }
            return $allNames;
        }, []);

        self::assertInstanceOf(Map::class, $map);
        self::assertIsArray($countedNames);
        self::assertSame(['Alice' => 2, 'Bob' => 1, 'Tiff' => 1, 'Bruce' => 1], $countedNames);
    }

    public function test_reduce_group_by(): void
    {
        $map = new Map([
            new Map(['name' => 'Alice', 'age' => 21]),
            new Map(['name' => 'Max', 'age' => 20]),
            new Map(['name' => 'Jane', 'age' => 20]),
        ]);

        $property = 'age';
        $groupedPeople = $map->reduce(function(Map $acc, Map $obj) use ($property) {
            $key = $obj->offsetGet($property);

            if (!$acc->offsetExists($key)) {
                $acc->offsetSet($key, new Map());
            }
            $acc->offsetGet($key)->push($obj);

            return $acc;
        }, new Map());

        self::assertInstanceOf(Map::class, $groupedPeople);
        self::assertInstanceOf(Map::class, $groupedPeople->get(20));
        self::assertInstanceOf(Map::class, $groupedPeople->get(20)->get(0));
        self::assertEquals([
            20 => [['name' => 'Max', 'age' => 20], ['name' => 'Jane', 'age' => 20]],
            21 => [['name' => 'Alice', 'age' => 21]]
        ], $groupedPeople->toArray());
    }

    public function test_reduce_remove_duplicate_items(): void
    {
        $map = new Map(['a', 'b', 'a', 'b', 'c', 'e', 'e', 'c', 'd', 'd', 'd', 'd']);
        $myArrayWithNoDuplicates = $map->reduce(function (array $previousValue, string $currentValue) {
            if (!in_array($currentValue, $previousValue, true)) {
                $previousValue[] = $currentValue;
            }
            return $previousValue;
        }, []);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(['a', 'b', 'c', 'e', 'd'], $myArrayWithNoDuplicates);
    }

    public function test_reduceRight(): void
    {
        $map = new Map([0, 1, 2, 3]);
        $sum = $map->reduceRight(fn($a, $b) => $a + $b);

        self::assertInstanceOf(Map::class, $map);
        self::assertIsInt($sum);
        self::assertSame(6, $sum);
    }

    public function test_reduceRight_flatten(): void
    {
        $map = new Map([new Map([0, 1]), new Map([2, 3]), new Map([4, 5])]);
        $flattened = $map->reduceRight(function(Map $a, Map $b) {
            return $a->concat($b);
        }, new Map());

        self::assertInstanceOf(Map::class, $flattened);
        self::assertSame([4, 5, 2, 3, 0, 1], $flattened->toArray());
    }

    public function test_reduceRight_concat_string(): void
    {
        $map = new Map(['1', '2', '3', '4', '5']);
        $right = $map->reduceRight(fn($prev, $curr) => $prev . $curr);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('54321', $right);
    }

    public function test_shift(): void
    {
        $map = new Map(['angel', 'clown', 'mandarin', 'surgeon']);
        $shifted = $map->shift();

        self::assertInstanceOf(Map::class, $map);
        self::assertSame(['clown', 'mandarin', 'surgeon'], $map->toArray());
        self::assertSame('angel', $shifted);
    }

    public function test_slice(): void
    {
        $map = new Map(['ant', 'bison', 'camel', 'duck', 'elephant']);

        self::assertSame([2 => 'camel', 3 => 'duck', 4 => 'elephant'], $map->slice(2)->toArray());
        self::assertSame([], $map->slice(2, 2)->toArray());
        self::assertSame([2 => 'camel', 3 => 'duck'], $map->slice(2, 4)->toArray());
        self::assertSame([1 => 'bison', 2 =>'camel', 3 => 'duck', 4 => 'elephant'], $map->slice(1, 5)->toArray());
        self::assertSame([1 => 'bison', 2 =>'camel', 3 => 'duck'], $map->slice(1, 4)->toArray());
        self::assertSame([3 => 'duck', 4 => 'elephant'], $map->slice(-2)->toArray());
        self::assertSame([2 =>'camel', 3 => 'duck'], $map->slice(2, -1)->toArray());
    }

    public function test_some(): void
    {
        $isBiggerThan10 = static function($element) {
            return $element > 10;
        };

        self::assertFalse(Map::from([2, 5, 8, 1, 4])->some($isBiggerThan10));
        self::assertTrue(Map::from([12, 5, 8, 1, 4])->some($isBiggerThan10));
    }

    public function test_sort(): void
    {
        $map = (new Map([-1, 4, 2, 0, 5, 1, 3, -2]))->sort();

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([-2, -1, 0, 1, 2, 3, 4, 5], $map->toArray());
    }

    public function test_sort_with_callback(): void
    {
        $map = (new Map([
            ['name' => 'Alex',   'grade' => 15],
            ['name' => 'Devlin', 'grade' => 15],
            ['name' => 'Eagle',  'grade' => 13],
            ['name' => 'Sam',    'grade' => 14],
        ]))->sort(function($first, $second) {
            return $first['grade'] - $second['grade'];
        });

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([
            ['name' => 'Eagle',  'grade' => 13],
            ['name' => 'Sam',    'grade' => 14],
            ['name' => 'Alex',   'grade' => 15],
            ['name' => 'Devlin', 'grade' => 15],
        ], $map->toArray());
    }

    public function test_splice(): void
    {
        $map = new Map(['angel', 'clown', 'mandarin', 'sturgeon']);
        $removed = $map->splice(2);

        self::assertInstanceOf(Map::class, $removed);
        self::assertSame(['angel', 'clown'], $map->toArray());
        self::assertSame(['mandarin', 'sturgeon'], $removed->toArray());
    }

    public function test_splice_with_delete_count(): void
    {
        $map = new Map(['parrot', 'anemone', 'blue', 'trumpet', 'sturgeon']);
        $removed = $map->splice(2, 2);

        self::assertInstanceOf(Map::class, $removed);
        self::assertSame(['parrot', 'anemone', 'sturgeon'], $map->toArray());
        self::assertSame(['blue', 'trumpet'], $removed->toArray());
    }

    public function test_splice_with_negative_delete_count(): void
    {
        $map = new Map(['angel', 'clown', 'mandarin', 'sturgeon']);
        $removed = $map->splice(-2, 1);

        self::assertInstanceOf(Map::class, $removed);
        self::assertSame(['angel', 'clown', 'sturgeon'], $map->toArray());
        self::assertSame(['mandarin'], $removed->toArray());
    }

    public function test_splice_with_delete_count_and_replacements(): void
    {
        $map = new Map(['angel', 'clown', 'trumpet', 'sturgeon']);
        $removed = $map->splice(0, 2, 'parrot', 'anemone', 'blue');

        self::assertInstanceOf(Map::class, $removed);
        self::assertSame(['parrot', 'anemone', 'blue', 'trumpet', 'sturgeon'], $map->toArray());
        self::assertSame(['angel', 'clown'], $removed->toArray());
    }

    public function test_splice_with_replacements_and_without_removing(): void
    {
        $map = new Map(['angel', 'clown', 'mandarin', 'sturgeon']);
        $removed = $map->splice(2, 0, 'drum', 'guitar');

        self::assertInstanceOf(Map::class, $removed);
        self::assertSame(['angel', 'clown', 'drum', 'guitar', 'mandarin', 'sturgeon'], $map->toArray());
        self::assertSame([], $removed->toArray());
    }

    public function test_toString(): void
    {
        $map = new Map([1, 2, 'a', '1a']);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('1,2,a,1a', $map->toString());
    }

    public function test_unshift(): void
    {
        $map = new Map([4, 5, 6]);
        $map->unshift(1, 2, 3);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([1, 2, 3, 4, 5, 6], $map->toArray());
    }

    public function test_unshift_multiple_time(): void
    {
        $map = new Map([4, 5, 6]);
        $map->unshift(1);
        $map->unshift(2);
        $map->unshift(3);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([3, 2, 1, 4, 5, 6], $map->toArray());
    }

    public function test_unshift_nested_array(): void
    {
        $map = new Map([0, 1, 2]);
        $map->unshift([-4, -3]);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame([[-4, -3], 0, 1, 2], $map->toArray());
    }

    public function test_at(): void
    {
        $map = new Map(['apple', 'banana', 'pear']);

        self::assertInstanceOf(Map::class, $map);
        self::assertNull($map->at(-4));
        self::assertSame('pear', $map->at(-1));
        self::assertSame('banana', $map->at(1));
    }

}