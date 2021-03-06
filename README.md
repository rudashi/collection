# PHP Collection

Easy and elegant wrapper for PHP arrays.

# Table of Contents
- [Installation](#installation)
- [Styles](#styles)
  - [JavaScript Array](#javascript-array)
  - [JavaScript Map](#javascript-map)
  - [JavaScript Set](#javascript-set)
  - [Collection](#collection)
- [Basic Usage](#basic-usage)
  - [Create](#create)
  - [Access](#access)
  - [Mutation](#mutation)
  - [Available Methods](#available-methods)
- [Documentation](#documentation)

## Installation

```bash
composer require rudashi/collection
```

## Styles

### JavaScript Array
* [@property length](#length)
* [at()](#at)
* [concat()](#concat)
* [copyWithin()](#copywithin)
* [entries()](#entries)
* [every()](#every)
* [fill()](#fill)
* [filter()](#filter)
* [find()](#find)
* [findIndex()](#findindex)
* [flat()](#flat)
* [flatMap()](#flatmap)
* [forEach()](#foreach)
* [from()](#from)
* [includes()](#includes)
* [indexOf()](#indexof)
* [isArray()](#isarray)
* [join()](#join)
* [keys()](#keys)
* [lastIndexOf()](#lastindexof)
* [map()](#map)
* [of()](#of)
* [pop()](#pop)
* [push()](#push)
* [reduce()](#reduce)
* [reduceRight()](#reduceright)
* [reverse()](#reverse)
* [shift()](#shift)
* [slice()](#slice)
* [some()](#some)
* [sort()](#sort)
* [splice()](#splice)
* [toString()](#tostring)
* [unshift()](#unshift)
* [values()](#values)

### JavaScript Map
* [@property size](#size)
* [clear()](#clear)
* [delete()](#delete)
* [entries()](#entries)
* [forEach()](#foreach)
* [get()](#get)
* [has()](#has)
* [keys()](#keys)
* [set()](#set)
* [values()](#values)

### JavaScript Set
* [@property size](#size)
* [add()](#add)
* [clear()](#clear)
* [delete()](#delete)
* [entries()](#entries)
* [foreach()](#foreach)
* [has()](#has)
* [keys()](#keys)
* [values()](#values)

### Collection
* all() : Returns the elements as a plain array
* count() : Counts the total number of elements
* first() : Returns the first element from Collection
* firstWhere() : Returns the first element with given key
* keys() : Returns the keys of the all elements in a new Collection
* values() : Returns the values of the all elements in a new Collection
* map() : Calls the passed function once for each element and returns a Collection
* toArray() : Returns the elements as a plain array
* toJson() : Returns the elements as a JSON string
* empty() : Alias to isEmpty()
* isEmpty() : Determine if the Collection is empty
* isNotEmpty() : Determine if the Collection is not empty

## Basic Usage
### Create

* [function map()](#map-function)
* [__construct()](#__construct)
* [from()](#from)
* [of()](#of)

### Access
* [@property length](#length)
* [all()](#all)
* [at()](#at)
* [count()](#count)
* [entries()](#entries)
* [every()](#every)
* [filter()](#filter)
* [find()](#find)
* [findIndex()](#findindex)
* [forEach()](#foreach)
* [get()](#get)
* [has()](#has)
* [includes()](#includes)
* [indexOf()](#indexof)
* [isArray()](#isarray)
* [keys()](#keys)
* [lastIndexOf()](#lastindexof)
* [reduce()](#reduce)
* [reduceRight()](#reduceright)
* [slice()](#slice)
* [some()](#some)
* [sort()](#sort)
* [toArray()](#toarray)
* [toString()](#tostring)
* [values()](#values)

### Mutation
* [add()](#add)
* [clear()](#clear)
* [concat()](#concat)
* [copyWithin()](#copywithin)
* [delete()](#delete)
* [fill()](#fill)
* [flat()](#flat)
* [flatMap()](#flatmap)
* [join()](#join)
* [map()](#map)
* [pop()](#pop)
* [push()](#push)
* [reverse()](#reverse)
* [set()](#set)
* [shift()](#shift)
* [splice()](#splice)
* [unshift()](#unshift)

### Available Methods
* [add()](#add)
* [all()](#all)
* [at()](#at)
* [clear()](#clear)
* [concat()](#concat)
* [copyWithin()](#copywithin)
* [count()](#count)
* [entries()](#entries)
* [every()](#every)
* [fill()](#fill)
* [filter()](#filter)
* [find()](#find)
* [findIndex()](#findindex)
* [flat()](#flat)
* [flatMap()](#flatmap)
* [forEach()](#foreach)
* [from()](#from)
* [has()](#has)
* [includes()](#includes)
* [indexOf()](#indexof)
* [isArray()](#isarray)
* [join()](#join)
* [keys()](#keys)
* [lastIndexOf()](#lastindexof)
* [map()](#map)
* [of()](#of)
* [pop()](#pop)
* [push()](#push)
* [reduce()](#reduce)
* [reduceRight()](#reduceright)
* [reverse()](#reverse)
* [set()](#set)
* [shift()](#shift)
* [slice()](#slice)
* [some()](#some)
* [sort()](#sort)
* [splice()](#splice)
* [toArray()](#toarray)
* [toString()](#tostring)
* [unshift()](#unshift)
* [values()](#values)

## Documentation

### map() function
```php
map([1, 2, 3]);
```

### __construct
```php
new \Rudashi\Map([[1, 2], [2, 4], [4, 8]]);
```

### length
Alias for the [count()](#count) method.
### size
Alias for the [count()](#count) method.

### add()
Adds a new element with a specified value to the end.
```php
new \Rudashi\Set([1, 2, 3])->add(5);
// [ 1, 2, 3, 5]
```
### all()
Returns all the items in the collection.
```php
new \Rudashi\Map([1, 2, 3])->all();
// [ 1, 2, 3 ]
```
### at()
Returns item at index.
```php
new \Rudashi\Map([1, 2, 3])->at(-1);
// 3
```
### clear()
Removes all elements.
```php
new \Rudashi\Map(['foo' => 'bar'])->clear();
// []
```
### concat()
Returns a new instance with merged values.
```php
new \Rudashi\Map(['a', 'b', 'c'])->concat(['d', 'e', 'f']);
// [ 'a', 'b', 'c', 'd', 'e', 'f' ]
```
### copyWithin()
Copies part of an array to another location in the same array and returns it without modifying its length.
```php
new \Rudashi\Map(['a', 'b', 'c', 'd', 'e'])->copyWithin(0, 3, 4);
// [ 'd', 'b', 'c', 'd', 'e' ]
```
### count()
Returns the number of elements.
```php
new \Rudashi\Map(['a', 'b', 'c'])->count();
// 3
```
### delete()
Removes the specified element.
```php
new \Rudashi\Map(['foo' => 'bar', 'bar' => 'baz'])->delete('bar');
// true
```
### entries()
Alias for the [toArray()](#toarray) method.
### every()
Determine if all items pass the test implemented by callback test.
```php
new \Rudashi\Map([3, 4, 9, 16])->every(function ($value) {
    return $value < 4;
});
// false
```
### fill()
Returns a new instance with changes all items, from a start index to an end index.
```php
new Map([1, 2, 3])->fill(4);
// [ 4, 4, 4 ]
```
### filter()
Returns a new instance with all elements that pass the test implemented by the provided callback.
```php
new \Rudashi\Map(['a', 'b', 'c'])->filter(fn($v) => $v !== 'a');
// [ 'b', 'c' ]
```
### find()
Returns the first matching element where the callback returns TRUE.
```php
new \Rudashi\Map(['a', 'b', 'c'])->find(fn($v) => $v === 'c');
// 'c'
```
### findIndex()
Returns the first matching element where the callback returns TRUE.
```php
new \Rudashi\Map(['a', 'b', 'c'])->findIndex(fn($v) => $v === 'c');
// 2
```
### flat()
Returns a new instance with all sub elements concatenated into it recursively up to the specified depth.
```php
new \Rudashi\Map([1, 2, [3, 4]])->flat();
// [1, 2, 3, 4]
```
### flatMap()
Returns a new instance formed by applying a given callback function to each element and then flattening the result by one level.
```php
new \Rudashi\Map([1, 2, 3, 4])->flatMap(fn ($x) => [$x, $x * 2]);
// [1, 2, 2, 4, 3, 6, 4, 8]
```
### forEach()
Method creates a new instance populated with the results of calling a provided function on every element.
```php
new \Rudashi\Map([1, 4, 9, 16])->forEach(function ($item, $key) {
    //
});
```
### from()
#### From a String
```php
\Rudashi\Map::from('foo')
// [ 'f', 'o', 'o' ]
```
#### From an Array
```php
\Rudashi\Map::from(['foo', 'bar', 'baz', 'foo'])
// [ 'foo', 'bar', 'baz', 'foo' ]
```
#### From a Map
```php
$mapper = new \Rudashi\Map([['1', 'a'], ['2', 'b']]);
\Rudashi\Map::from(mapper->values());
// [ ['1', 'a'], ['2', 'b'] ];

\Rudashi\Map::from(mapper->keys());
// [ 0, 1 ];
```
#### From an Integer (range)
```php
\Rudashi\Map::from(5)
// [ 0, 1, 2, 3, 4 ]
```
#### From an Array with callback
```php
\Rudashi\Map::from([1, 2, 3], fn($value) => $value + $value)))
// [ 2, 4, 6 ]
```
#### From a JSON
```php
\Rudashi\Map::from('{"a": "b"}')
// [ 'a' => 'b' ]
```
### get()
Returns a specified element by a key.
```php
new \Rudashi\Map([1 => 'a', 4 => 'a'])->get(4);
// 'a'
```
### has()
Determines whether it contains the given element.
```php
new \Rudashi\Map([1 => 'a', 4 => 'a'])->has(4);
// true
```
### includes()
Determines whether it contains the given element.
```php
new \Rudashi\Map([1, 4, 9, 16])->includes(4);
// true
```
### indexOf()
Returns the first matching index which a given element can be found.
```php
new \Rudashi\Map(['a', 'b', 'c'])->indexOf('c');
// 2
```
### isArray()
Determines whether the passed value is an Array.
```php
new Map::isArray([1, 2, 3, 4]);
// true
```
### join()
Concatenates the string representation of all elements.
```php
new \Rudashi\Map(['a', 'b', 'c'])->join();
// a,b,c
```
### keys()
Returns a new instance that contains the keys.
```php
new \Rudashi\Map(['a', 'b', 'c'])->keys();
// [ 0, 1, 2 ]
```
### lastIndexOf()
Returns the last matching index which a given element can be found.
```php
new \Rudashi\Map(['c', 'a', 'b', 'c'])->lastIndexOf('c');
// 3
```
### map()
Method creates a new instance populated with the results of calling a provided function on every element.
```php
new \Rudashi\Map([1, 4, 9, 16])->map(function ($item, $key) {
    return $item * 2;
});
// [ 2, 8, 18, 32 ]
```
### of()
```php
new \Rudashi\Map::of([[1, 2], [2, 4], [4, 8]]);
```
### pop()
Removes the last element and returns that element.
```php
new \Rudashi\Map(['a', 'b', 'c'])->pop();
// 'c'
```
### push()
Method adds one or more elements to the end.
```php
new \Rudashi\Map(['pigs', 'goats', 'sheep'])->push('cows', 'cats');
// [ 'pigs', 'goats', 'sheep', 'cows', 'cats' ]
```
### reduce()
Execute a callback over each item reducing to a single value.
```php
new \Rudashi\Map(['1', '2', '3', '4', '5'])->reduce(fn($prev, $curr) => $prev + $curr);
// '12345'
```
### reduceRight()
Execute a callback over each item (from right-to-left) reducing to a single value.
```php
new \Rudashi\Map(['1', '2', '3', '4', '5'])->reduce(fn($prev, $curr) => $prev + $curr);
// '54321'
```
### reverse()
Returns a new instance with the order of the elements reversed.
```php
new \Rudashi\Map(['pigs', 'goats', 'sheep'])->push('cows', 'cats');
// [ 'pigs', 'goats', 'sheep', 'cows', 'cats' ]
```
### set()
Adds or updates an element with a specified key and a value.
```php
new \Rudashi\Map()->set('bar', 'foo');
// [ 'bar', 'foo' ]
```
### shift()
Removes the first element and returns that element.
```php
new \Rudashi\Map(['a', 'b', 'c'])->shift();
// 'a'
```
### slice()
Returns a new instance with portion of items.
```php
new \Rudashi\Map(['Banana', 'Orange', 'Lemon', 'Apple', 'Mango'])->slice(1, 3);
// ['Orange','Lemon']
```
### some()
Method tests whether at least one element passes the test.
```php
new \Rudashi\Map([1, 2, 3])->some(fn($element) => $element % 2 === 0);
// true
```
### sort()
Returns a new sorted instance.
```php
new \Rudashi\Map([-1, 4, 2, 0, 5, 1, 3, -2])->sort();
// [-2, -1, 0, 1, 2, 3, 4, 5]
```
### splice()
Modifies instance and returns a new instance with existing elements removed or replaced.
```php
new \Rudashi\Map(['Jan', 'March', 'April', 'June'])->splice(1, 0, 'Feb');
// ['Jan', 'Feb', 'March', 'April', 'June']
```
### toArray()
Returns all the items as plain array.
```php
new \Rudashi\Map([1, 2, 3])->toArray();
// [ 1, 2, 3 ]
```
### toString()
Alias for the [join()](#join) method.
### unshift()
Adds one or more elements to the beginning and returns the new instance.
```php
new \Rudashi\Map(['a', 'b', 'c'])->unshift(1);
// [1, 'a', 'b', 'c']
```
### values()
Returns a new instance that contains the values with reset keys.
```php
new \Rudashi\Map(['a', 'b', 'c'])->values();
// [ 'a', 'b', 'c' ]
```