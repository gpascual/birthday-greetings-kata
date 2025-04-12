<?php

namespace BirthdayGreetings\Functional;

/**
 * @template T
 * @template G
 * @param iterable<T> $iterable
 * @param callable<T, G> $mapper
 * @return iterable<T>
 */
function map(iterable $iterable, callable $mapper): iterable
{
    foreach ($iterable as $key => $value) {
        yield $key => $mapper($value);
    }
}
