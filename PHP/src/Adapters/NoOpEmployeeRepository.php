<?php

namespace BirthdayGreetings\Adapters;

use BirthdayGreetings\EmployeeRepository;

class NoOpEmployeeRepository implements EmployeeRepository
{
    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return [];
    }
}
