<?php

namespace Zheltikov\Arrays;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Interface KeyedTraversable
 * @package Zheltikov\Arrays
 */
interface KeyedTraversable extends
    ArrayAccess,
    Countable,
    Iterator
{
}
