<?php

namespace BirthdayGreetings\Tests;

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\MessageSender;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;

class BirthdayServiceTestCase extends TestCase
{
    protected MockInterface&EmployeeRepository $employeeRepository;
    protected MockInterface&MessageSender $messageSender;
    protected MockInterface&LoggerInterface $logger;
    protected BirthdayService $sut;
}
