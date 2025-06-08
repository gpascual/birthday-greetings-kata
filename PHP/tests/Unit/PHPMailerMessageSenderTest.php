<?php

use BirthdayGreetings\Adapters\PHPMailerMessageSender;
use BirthdayGreetings\Employee;
use BirthdayGreetings\Message;
use BirthdayGreetings\Tests\PHPMailerMessageSenderTestCase;

pest()->extends(PHPMailerMessageSenderTestCase::class);

describe(PHPMailerMessageSender::class, function () {
    beforeEach(function () {
        $this->sut = new PHPMailerMessageSender('localhost', $this::SMTP_PORT);
    });

    afterEach(function () {
        $this->deleteAllEmails();
    });

    it('sends a message', function () {
        $this->sut->sendMessage(
            Message::birthdayGreeting(new Employee('Jane', 'Doe', '2000/01/01', 'jane.doe@gmail.com'))
        );

        $emails = $this->getEmails();
        $this->assertCount(1, $emails, 'Expected exactly one email to be sent');

        $email = $emails[0];
        $this->assertEquals('Happy Birthday!', $email['subject']);
        $this->assertEquals(
            <<<'STR'
Happy Birthday, dear Jane!


STR
            ,
            $email['text']
        );
        $this->assertCount(1, $email['to']);
        $this->assertEquals('jane.doe@gmail.com', $email['to'][0]['address']);
    });
});
