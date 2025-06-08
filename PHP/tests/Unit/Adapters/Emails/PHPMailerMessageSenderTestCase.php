<?php

namespace BirthdayGreetings\Tests\Unit\Adapters\Emails;

use BirthdayGreetings\Adapters\Emails\PHPMailerMessageSender;
use BirthdayGreetings\Tests\SmtpTesting;
use BirthdayGreetings\Tests\TestCase;

class PHPMailerMessageSenderTestCase extends TestCase
{
    use SmtpTesting;

    protected PHPMailerMessageSender $sut;
}
