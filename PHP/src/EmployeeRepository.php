<?php

namespace BirthdayGreetings;

interface EmployeeRepository
{
    /** @return iterable<Employee> */
    public function findEmployeesCelebratingBirthdayOn(XDate $xDate): iterable;
}
