<?php
namespace BirthdayGreetings\Functional;

/**
 * @template T
 * @param iterable<T> $iterable
 * @param callable<T, bool> $predicate
 * @return iterable<T>
 */
function filter(iterable $iterable, callable $predicate): iterable
{
    foreach ($iterable as $item) {
        if ($predicate($item)) {
            yield $item;
        }
    }
}
