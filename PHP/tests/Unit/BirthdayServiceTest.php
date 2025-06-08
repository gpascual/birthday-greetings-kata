<?php

use BirthdayGreetings\BirthdayGreetingMessage;
use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\MessageSender;
use BirthdayGreetings\Tests\BirthdayServiceTestCase;
use BirthdayGreetings\XDate;
use Psr\Log\LoggerInterface;
use Rx\Observable;
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
                    Observable::fromArray([
                        $anEmployeeCelebratingBirthday,
                        $anotherEmployeeCelebratingBirthday,
                    ])
                );

            $this->sut->sendGreetings(new XDate($today));

            $this->messageSender
                ->shouldHaveReceived(
                    'sendMessage',
                    [Mockery::isEqual(BirthdayGreetingMessage::create($anEmployeeCelebratingBirthday))]
                );
            $this->messageSender
                ->shouldHaveReceived(
                    'sendMessage',
                    [Mockery::isEqual(BirthdayGreetingMessage::create($anotherEmployeeCelebratingBirthday))]
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
                        Observable::fromArray([
                            $anEmployeeCelebratingBirthday,
                            $anotherEmployeeCelebratingBirthday,
                        ])
                    );
                $expectedSendingException = new RuntimeException('something went wrong');
                $this->messageSender
                    ->allows('sendMessage')
                    ->with(Mockery::isEqual(BirthdayGreetingMessage::create($anEmployeeCelebratingBirthday)))
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
                        [Mockery::isEqual(BirthdayGreetingMessage::create($anotherEmployeeCelebratingBirthday))]
                    );
            }
        );
    });
});
