<?php

namespace BirthdayGreetings;

require_once __DIR__ . '/../../vendor/autoload.php';

class Main
{
    public static function main(): void
    {
        $service = BirthdayService::constructTheUglyWay('employee_data.txt', 'localhost', 25);
        $service->sendGreetings((new XDate()));
    }
}

if (php_sapi_name() === 'cli') {
    Main::main();
}
