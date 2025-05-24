<?php

namespace birthday_greetings;

use BirthdayGreetings\Adapters\CsvEmployeeRepository;
use BirthdayGreetings\Adapters\MailMessageSender;
use BirthdayGreetings\BirthdayService;
use BirthdayGreetings\XDate;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Rx\Scheduler;

class AcceptanceTest extends TestCase
{
    private const SMTP_PORT = 1025; // MailDev's default SMTP port
    private const API_URL = 'http://localhost:1080';
    private BirthdayService $birthdayService;

    protected function setUp(): void
    {
        $this->birthdayService = new BirthdayService(
            new CsvEmployeeRepository('employee_data.txt'),
            new MailMessageSender('localhost', self::SMTP_PORT),
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

    private function deleteAllEmails(): void
    {
        file_get_contents(self::API_URL . '/email/all', false, stream_context_create([
            'http' => ['method' => 'DELETE']
        ]));
    }

    private function getEmails(): array
    {
        $response = file_get_contents(self::API_URL . '/email');
        return json_decode($response, true) ?? [];
    }

    public function testWillSendGreetingsWhenItsSomebodysBirthday(): void
    {
        $this->birthdayService->sendGreetings((new XDate('2008/10/08')));

        // Wait for email to be processed
        sleep(1);

        $emails = $this->getEmails();
        $this->assertCount(1, $emails, 'Expected exactly one email to be sent');

        $email = $emails[0];
        $this->assertEquals('Happy Birthday!', $email['subject']);
        $this->assertEquals(
            <<<'STR'
Happy Birthday, dear John!


STR
            ,
            $email['text']
        );
        $this->assertCount(1, $email['to']);
        $this->assertEquals('john.doe@foobar.com', $email['to'][0]['address']);
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
