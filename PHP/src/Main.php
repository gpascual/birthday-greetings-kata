<?php

namespace BirthdayGreetings;

use BirthdayGreetings\Adapters\CsvEmployeeRepository;
use BirthdayGreetings\Adapters\MailMessageSender;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Rx\Scheduler;

require_once __DIR__ . '/../../vendor/autoload.php';

class Main
{
    public static function main(): void
    {
        Scheduler::setDefaultFactory(static function () {
            static $scheduler = new Scheduler\ImmediateScheduler();
            return $scheduler;
        });

        $service = new BirthdayService(
            new CsvEmployeeRepository('employee_data.txt'),
            new MailMessageSender('localhost', 25),
            new Logger('BirthdayGreetings', [new ErrorLogHandler()])
        );
        $service->sendGreetings((new XDate()));
    }
}

if (php_sapi_name() === 'cli') {
    Main::main();
}
