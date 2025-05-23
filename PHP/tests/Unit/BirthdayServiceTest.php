<?php

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;
use BirthdayGreetings\Tests\BirthdayServiceTestCase;
use BirthdayGreetings\XDate;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

pest()->extend(BirthdayServiceTestCase::class);

describe('BirthdayService', function () {
    beforeEach(function () {
        $this->employeeRepository = mock(EmployeeRepository::class);
        $this->messageSender = spy(MessageSender::class);
        $this->sut = new BirthdayService(
            $this->employeeRepository,
            $this->messageSender,
            new Logger('BirthdayGreetings', [new ErrorLogHandler()])
        );
    });

    it(
        'sends greeting messages to employees celebrating their birthday only',
        function () {
            $today = '1990/12/31';
            $anEmployeeCelebratingBirthday = new Employee('Jane', 'Doe', $today, 'janedoe@foobar.com');
            $anotherEmployeeCelebratingBirthday = new Employee('Mark', 'Doe', $today, 'janedoe@foobar.com');
            $this->employeeRepository
                ->allows('findEmployeesCelebratingBirthdayOn')
                ->andReturns(
                    new ArrayIterator([
                        $anEmployeeCelebratingBirthday,
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
