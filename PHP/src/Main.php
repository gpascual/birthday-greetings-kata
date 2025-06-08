<?php

namespace BirthdayGreetings;

use BirthdayGreetings\Adapters\CsvEmployeeRepository;
use BirthdayGreetings\Adapters\Emails\PHPMailerMessageSender;
use BirthdayGreetings\Emails\BirthdayGreetingEmailMessageComposer;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Rx\Scheduler;
use Rx\SchedulerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

final class Main
{
    public static function main(): void
    {
        Scheduler::setDefaultFactory(static function (): SchedulerInterface {
            static $scheduler = new Scheduler\ImmediateScheduler();
            return $scheduler;
        });

        $service = new BirthdayService(
            new CsvEmployeeRepository('employee_data.txt'),
            new PHPMailerMessageSender(new BirthdayGreetingEmailMessageComposer(), 'localhost', 25),
            new Logger('BirthdayGreetings', [new ErrorLogHandler()])
        );
        $service->sendGreetings((new XDate()));
    }
}

if (php_sapi_name() === 'cli') {
    Main::main();
}
