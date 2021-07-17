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

## Examples!

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

// Creating a keyset
$items = keyset(['a', 'b', 'c']);

// Checking if a keyset contains a value
$items->contains('a');    // true

// Adding/removing items
$items[] = 'd';        // keyset(['a', 'b', 'c', 'd'])
$items[] = 'a';        // keyset(['a', 'b', 'c', 'd'])
unset($items['b']);    // keyset(['a', 'c', 'd'])

// Getting the length
count($items);      // 3
$items->count();    // 3

// Iterating
foreach ($items as $item) {
    echo $item;
}

// Equality checks. `match` returns false if the order does not match.
Keyset::equal(keyset([1]), keyset([1]));          // true
Keyset::match(keyset([1, 2]), keyset([2, 1]));    // false
Keyset::equal(keyset([1, 2]), keyset([2, 1]));    // true

// Combining keysets
Keyset::union(keyset([1, 2]), keyset([2, 3]));    // keyset([1, 2, 3])

// Converting from an iterable
keyset(vec([1, 2, 1]));                // keyset([1, 2])
keyset([20, 21]);                      // keyset([20, 21])
keyset(dict(['key1' => 'value1']));    // keyset(['value1'])

// Type checks
$items instanceof Keyset;    // true

/************************************ Dict ************************************/

// Creating a dict
$items = dict(['a' => 1, 'b' => 3]);

// Accessing items by key
$items['a'];      // 1
$items['foo'];    // throws OutOfBoundsException

// Inserting, updating or removing values in a dict
$items['a'] = 42;      // dict(['a' => 42, 'b' => 3])
$items['z'] = 100;     // dict(['a' => 42, 'b' => 3, 'z' => 100])
unset($items['b']);    // dict(['a' => 42, 'z' => 100])

// Getting the keys
Vec::keys(dict(['a' => 1, 'b' => 3]));    // vec(['a', 'b'])

// Getting the values
vec(dict(['a' => 1, 'b' => 3]));    // vec([1, 3])

// Getting the length.
count($items);      // 2
$items->count();    // 2

// Checking if a dict contains a key or value
$items->contains_key('a');    // true
$items->contains(3);          // true

// Iterating values
foreach ($items as $value) {
    echo $value;    // e.g. 1
}

// Iterating keys and values
foreach ($items as $key => $value) {
    echo $key;      // e.g. 'a'
    echo $value;    // e.g. 1
}

// Equality checks. `match` returns false if the order does not match.
Dict::equ(dict(), dict());                                          // true
Dict::match(dict([0 => 10, 1 => 11]), dict([1 => 11, 0 => 10]));    // false
Dict::equal(dict([0 => 10, 1 => 11]), dict([1 => 11, 0 => 10]));    // true

// Combining dicts (last item wins)
Dict::merge(dict([10 => 1, 20 => 2]), dict([30 => 3, 10 => 0]));
// dict([10 => 0, 20 => 2, 30 => 3])

// Converting from an iterable
dict(vec(['a', 'b']));    // dict([0 => 'a', 1 => 'b'])
dict(['a' => 5]);         // dict(['a' => 5])

// Type checks.
$items instanceof Dict;    // true

```
