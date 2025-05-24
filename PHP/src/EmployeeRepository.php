<?php

namespace BirthdayGreetings;

use Rx\Observable;

interface EmployeeRepository
{
    public function findEmployeesCelebratingBirthdayOn(XDate $xDate): Observable;
}
