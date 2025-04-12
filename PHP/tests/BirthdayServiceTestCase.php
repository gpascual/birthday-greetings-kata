<?php

namespace BirthdayGreetings\Tests;

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\MessageSender;
use Mockery\MockInterface;

class BirthdayServiceTestCase extends TestCase
{
    protected MockInterface&EmployeeRepository $employeeRepository;
    protected MockInterface&MessageSender $messageSender;
    protected BirthdayService $sut;
}
