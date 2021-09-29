# PHP Collection

Easy and elegant wrapper for PHP arrays.

# Table of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
  - [Create](#create)
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

## Documentation

### map() function

```php
map([1, 2, 3]);
```

### __construct

```php
new \Rudashi\Map([[1, 2], [2, 4], [4, 8]]);
```

### from

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
