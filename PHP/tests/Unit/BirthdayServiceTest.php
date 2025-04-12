<?php

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\MessageSender;
use BirthdayGreetings\Tests\BirthdayServiceTestCase;
use BirthdayGreetings\XDate;

pest()->extend(BirthdayServiceTestCase::class);

describe('BirthdayService', function () {
    beforeEach(function () {
        $this->employeeRepository = mock(EmployeeRepository::class);
        $this->messageSender = mock(MessageSender::class);
        $this->sut = new BirthdayService(
            $this->employeeRepository,
            $this->messageSender
        );
    });

    it(
        'retrieves all employees',
        function () {
            $arrayIterator = new ArrayIterator([new Employee('John', 'Doe', '1999/01/01', 'johndoe@foobar.com')]);
            $this->employeeRepository
                ->allows('getAll')
                ->andReturns($arrayIterator);

            $this->sut->sendGreetings(new XDate('1999/01/01'));

            $this->employeeRepository->shouldHaveReceived('getAll');
        }
    );
});
