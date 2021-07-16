<?php

namespace Zheltikov\Arrays;

/**
 * @param iterable $arr
 * @return \Zheltikov\Arrays\Dict
 */
function dict(iterable $arr = []): Dict
{
    return Dict::create($arr);
}

/**
 * @param iterable $arr
 * @return \Zheltikov\Arrays\Keyset
 */
function keyset(iterable $arr = []): Keyset
{
    return Keyset::create($arr);
}

/**
 * @param iterable $arr
 * @return \Zheltikov\Arrays\Vec
 */
function vec(iterable $arr = []): Vec
{
    return Vec::create($arr);
}
