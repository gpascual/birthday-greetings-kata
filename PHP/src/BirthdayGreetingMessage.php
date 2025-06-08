<?php

namespace BirthdayGreetings;

final readonly class BirthdayGreetingMessage implements Message
{
    private function __construct(public Employee $employee)
    {
    }

    public static function create(Employee $employee): Message
    {
        return new self($employee);
    }
}
