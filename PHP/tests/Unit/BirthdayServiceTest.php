<?php

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;
use BirthdayGreetings\Tests\BirthdayServiceTestCase;
use BirthdayGreetings\XDate;

pest()->extend(BirthdayServiceTestCase::class);

describe('BirthdayService', function () {
    beforeEach(function () {
        $this->employeeRepository = mock(EmployeeRepository::class);
        $this->messageSender = spy(MessageSender::class);
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
            $this->messageSender->allows('sendMessage');

            $this->sut->sendGreetings(new XDate('1999/01/01'));

            $this->employeeRepository->shouldHaveReceived('getAll');
        }
    );

    it(
        'sends greeting messages to employees celebrating their birthday only',
        function () {
            $today = '1990/12/31';
            $anEmployeeCelebratingBirthday = new Employee('Jane', 'Doe', $today, 'janedoe@foobar.com');
            $anotherEmployeeCelebratingBirthday = new Employee('Mark', 'Doe', $today, 'janedoe@foobar.com');
            $this->employeeRepository
                ->allows('getAll')
                ->andReturns(
                    new ArrayIterator([
                        new Employee('John', 'Doe', '1990/12/30', 'johndoe@foobar.com'),
                        $anEmployeeCelebratingBirthday,
                        new Employee('Susan', 'Doe', '1991/01/01', 'janedoe@foobar.com'),
                        $anotherEmployeeCelebratingBirthday,
                    ])
                );

            $this->sut->sendGreetings(new XDate($today));

            $this->messageSender
                ->shouldHaveReceived(
                    'sendMessage',
                    [Mockery::isEqual(Message::birthdayGreeting($anEmployeeCelebratingBirthday))]
                );
            $this->messageSender
                ->shouldHaveReceived(
                    'sendMessage',
                    [Mockery::isEqual(Message::birthdayGreeting($anotherEmployeeCelebratingBirthday))]
                );
        }
    );
});
