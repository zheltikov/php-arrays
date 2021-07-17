# php-arrays

A PHP library that provides better arrays than native PHP ones.

## Installation

To install this library use Composer:

```shell
$ composer require zheltikov/php-arrays
```

## Usage

This library provides three better array classes:

- `Vec` is an ordered, iterable data structure.
- `Keyset` is an ordered data structure without duplicates. It can only contain `string` or `int` values.
- `Dict` is an ordered key-value data structure. Keys must be `string`s or `int`s.

Each of these array classes implement the `ArrayAccess`, `Countable` and `Iterator` interfaces for convenience. These
array classes can be type-targeted using the `AnyArray` interface, which they all implement.

To create a new instance of one of these arrays you can use their static method `create()`, optionally passing an
`iterable` to use as initial data.

To retrieve a plain PHP array, you can use their `toArray()` instance method.

There are also available some shorthand creator functions, which are wrappers around the static `create()` method:

- `vec()`
- `keyset()`
- `dict()`

## Example

```php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Zheltikov\Arrays\{Vec, Keyset, Dict};
use function Zheltikov\Arrays\{vec, keyset, dict};

/************************************* Vec ************************************/

// Creating a vec
$items = vec(['a', 'b', 'c']);

// Accessing items by index
$items[0];    // 'a'
$items[3];    // throws `OutOfBoundsException`

// Modifying items
$items[0] = 'xx';    // vec(['xx', 'b', 'c'])
$items[] = 'd';      // vec(['xx', 'b', 'c', 'd'])

// Getting the length
count($items);      // 4
$items->count();    // 4

// Seeing if a vec contains a value or index
$items->contains('a');     // true
$items->containsKey(2);    // true

// Iterating
foreach ($items as $item) {
  echo $item;
}

// Iterating with the index
foreach ($items as $index => $item) {
  echo $index;    // e.g. 0
  echo $item;     // e.g. 'a'
}

// Equality checks. Elements are recursively compared with ===
Vec::equal(vec([1]), vec([1]));          // true
Vec::equal(vec([1, 2]), vec([2, 1]));    // false

// Combining vecs
Vec::concat(vec([1]), vec([2, 3]));    // vec([1, 2, 3])

// Removing items at an index
$items = vec(['a', 'b', 'c']);
$n = 1;
unset($items[$n]);    // vec(['a', 'c'])

// Converting from an iterable
vec(keyset([10, 11]));              // vec([10, 11])
vec([20, 21]);                      // vec([20, 21])
vec(dict(['key1' => 'value1']));    // vec(['value1'])

// Type checks
$items instanceof Vec;    // true

/*********************************** Keyset ***********************************/



/************************************ Dict ************************************/

```
