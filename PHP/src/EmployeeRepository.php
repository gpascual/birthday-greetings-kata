<?php

namespace BirthdayGreetings;

interface EmployeeRepository
{
    /** @return iterable<Employee> */
    public function getAll(): iterable;
}
