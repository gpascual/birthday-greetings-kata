<?php

namespace BirthdayGreetings\Tests\Unit\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessageSender;
use BirthdayGreetings\Tests\SmtpTesting;
use BirthdayGreetings\Tests\TestCase;

class EmailMessageSenderTestCase extends TestCase
{
    use SmtpTesting;

    protected EmailMessageSender $sut;
}
