<?php

namespace BirthdayGreetings;

require_once __DIR__ . '/../../vendor/autoload.php';

class Main
{
    public static function main(): void
    {
        BirthdayService::sendGreetingsUgly('employee_data.txt', new XDate(), 'localhost', 25);
    }
}

if (php_sapi_name() === 'cli') {
    Main::main();
}
