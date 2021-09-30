# PHP Collection

Easy and elegant wrapper for PHP arrays.

# Table of Contents
- [Installation](#installation)
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

## Basic Usage

### Create

* [function map()](#map-function)
* [__construct()](#__construct)
* [from()](#from)

### Access

* [@property length](#length)
* [all()](#all)
* [count()](#count)
* [filter()](#filter)
* [keys()](#keys)
* [toArray()](#toarray)
* [values()](#values)

### Mutation

* [concat()](#concat)
* [copyWithin()](#copywithin)
* [map()](#map)
* [push()](#push)

### Available Methods

* [all()](#all)
* [concat()](#concat)
* [copyWithin()](#copywithin)
* [filter()](#filter)
* [from()](#from)
* [keys()](#keys)
* [map()](#map)
* [values()](#values)
* [toArray()](#toarray)
* [count()](#count)
* [push()](#push)

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

### all()
Returns all the items in the collection.
```php
new \Rudashi\Map([1, 2, 3])->all();
// [ 1, 2, 3 ]
```
### concat()
Returns the new instance with merged values.
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
### filter()
Returns new instance with all elements that pass the test implemented by the provided callback.
```php
new \Rudashi\Map(['a', 'b', 'c'])->filter(fn($v) => $v !== 'a');
// [ 'b', 'c' ]
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
### keys()
Returns a new instance that contains the keys.
```php
new \Rudashi\Map(['a', 'b', 'c'])->keys();
// [ 0, 1, 2 ]
```
### map()
Method creates a new instance populated with the results of calling a provided function on every element.
```php
new \Rudashi\Map([1, 4, 9, 16])->map(function ($item, $key) {
    return $item * 2;
});
// [ 2, 8, 18, 32 ]
```
### push()
Method adds one or more elements to the end.
```php
new \Rudashi\Map(['pigs', 'goats', 'sheep'])->push('cows', 'cats');
// [ 'pigs', 'goats', 'sheep', 'cows', 'cats' ]
```
### toArray()
Returns all the items as plain array.
```php
new \Rudashi\Map([1, 2, 3])->toArray();
// [ 1, 2, 3 ]
```
### values()
Returns a new instance that contains the values with reset keys.
```php
new \Rudashi\Map(['a', 'b', 'c'])->values();
// [ 'a', 'b', 'c' ]
```