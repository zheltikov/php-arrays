<?php

namespace Zheltikov\Arrays;

/**
 * Shorthand function to create a new dict.
 *
 * @param iterable $arr The input initial data.
 * @return \Zheltikov\Arrays\Dict A new dict.
 */
function dict(iterable $arr = []): Dict
{
    return Dict::create($arr);
}

/**
 * Shorthand function to create a new keyset.
 *
 * @param iterable $arr The input initial data.
 * @return \Zheltikov\Arrays\Keyset A new keyset.
 */
function keyset(iterable $arr = []): Keyset
{
    return Keyset::create($arr);
}

/**
 * Shorthand function to create a new vec.
 *
 * @param iterable $arr The input initial data.
 * @return \Zheltikov\Arrays\Vec A new vec.
 */
function vec(iterable $arr = []): Vec
{
    return Vec::create($arr);
}
