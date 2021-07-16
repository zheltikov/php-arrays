# php-arrays

A PHP library that provides better arrays than native PHP ones.

## Installation

To install this library use Composer:

```shell
$ composer require zheltikov/php-arrays
```

## Usage

This library provides three better array classes:

- `Vec`: an ordered, iterable data structure.
- `Keyset`: an ordered data structure without duplicates. It can only contain `string` or `int` values.
- `Dict`: an ordered key-value data structure. Keys must be `string`s or `int`s.

Each of these array classes implement the `ArrayAccess`, `Countable` and `Iterator` interfaces for convenience. These
array classes can be type-targeted using the `AnyArray` interface, which they all implement.

To create a new instance of one of these arrays you can use their static method `create()`, optionally passing an
`iterable` to use as initial data.

To retrieve a plain PHP array, you can use their `toArray()` instance method.

There are also available some shorthand creator functions, which are wrappers around the static `create()` method:

- `vec()`
- `keyset()`
- `dict()`
