<?php

namespace BirthdayGreetings\Tests;

use BirthdayGreetings\Adapters\PHPMailerMessageSender;

class PHPMailerMessageSenderTestCase extends TestCase
{
    use SmtpTesting;

    protected PHPMailerMessageSender $sut;
}
