<?php

namespace birthday_greetings;

use BirthdayGreetings\Adapters\CsvEmployeeRepository;
use BirthdayGreetings\Adapters\Emails\PHPMailerMessageSender;
use BirthdayGreetings\Emails\BirthdayGreetingEmailMessageComposer;
use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\Tests\SmtpTesting;
use BirthdayGreetings\XDate;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Rx\Scheduler;

class AcceptanceTest extends TestCase
{
    use SmtpTesting;

    private BirthdayService $birthdayService;

    protected function setUp(): void
    {
        $this->birthdayService = new BirthdayService(
            new CsvEmployeeRepository('employee_data.txt'),
            new PHPMailerMessageSender(new BirthdayGreetingEmailMessageComposer(), 'localhost', self::SMTP_PORT),
            new Logger('BirthdayGreetings', [new ErrorLogHandler()])
        );
        $this->deleteAllEmails();
    }

    public static function setUpBeforeClass(): void
    {
        Scheduler::setDefaultFactory(static function () {
            static $scheduler = new Scheduler\ImmediateScheduler();
            return $scheduler;
        });
    }

    public function testWillSendGreetingsWhenItsSomebodysBirthday(): void
    {
        $this->birthdayService->sendGreetings((new XDate('2008/10/08')));

        // Wait for email to be processed
        sleep(1);

        $emails = $this->getEmails();
        $this->assertCount(1, $emails, 'Expected exactly one email to be sent');

        $email = $emails[0];
        $this->assertCount(1, $email['to']);
        $this->assertEquals('Happy Birthday!', $email['subject']);
        $this->assertEquals(
            <<<'STR'
Happy Birthday, dear John!


STR,
            $email['text']
        );
        $this->assertEquals([['address' => 'john.doe@foobar.com', 'name' => '']], $email['to']);
    }

    public function testWillNotSendEmailsWhenNobodysBirthday(): void
    {
        $this->birthdayService->sendGreetings((new XDate('2008/01/01')));

        // Wait for any potential emails to be processed
        sleep(1);

        $emails = $this->getEmails();
        $this->assertCount(0, $emails, 'Expected no emails to be sent');
    }
}
