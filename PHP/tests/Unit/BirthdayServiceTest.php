<?php

use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;
use BirthdayGreetings\Tests\BirthdayServiceTestCase;
use BirthdayGreetings\XDate;
use Psr\Log\LoggerInterface;
use Rx\Scheduler;
use Rx\Scheduler\ImmediateScheduler;

pest()->extend(BirthdayServiceTestCase::class);

beforeAll(function () {
    Scheduler::setDefaultFactory(static function () {
        static $scheduler = new ImmediateScheduler();
        return $scheduler;
    });
});

describe('BirthdayService', function () {
    beforeEach(function () {
        $this->employeeRepository = mock(EmployeeRepository::class);
        $this->messageSender = spy(MessageSender::class);
        $this->logger = spy(LoggerInterface::class);
        $this->sut = new BirthdayService(
            $this->employeeRepository,
            $this->messageSender,
            $this->logger
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

    describe('when a sending fails', function () {
        it(
            'logs the exception and continues',
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
                $expectedSendingException = new RuntimeException('something went wrong');
                $this->messageSender
                    ->allows('sendMessage')
                    ->with(Mockery::isEqual(Message::birthdayGreeting($anEmployeeCelebratingBirthday)))
                    ->andThrow($expectedSendingException);

                $this->sut->sendGreetings(new XDate($today));

                $this->logger
                    ->shouldHaveReceived(
                        'error',
                        [
                            "Error processing employee data: " . $expectedSendingException->getMessage(),
                            ['exception' => $expectedSendingException]
                        ]
                    );
                $this->messageSender
                    ->shouldHaveReceived(
                        'sendMessage',
                        [Mockery::isEqual(Message::birthdayGreeting($anotherEmployeeCelebratingBirthday))]
                    );
            }
        );
    });
});
