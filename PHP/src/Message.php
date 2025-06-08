<?php

namespace BirthdayGreetings;

final readonly class Message
{
    public function __construct(public Employee $employee)
    {
    }

    public static function birthdayGreeting(Employee $employee): Message
    {
        return new self($employee);
    }
}
