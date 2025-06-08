<?php

use BirthdayGreetings\Adapters\Emails\SymfonyMailerMessageSender;
use BirthdayGreetings\Emails\BirthdayGreetingEmailMessageComposer;
use BirthdayGreetings\Employee;
use BirthdayGreetings\Message;
use BirthdayGreetings\Tests\Unit\Adapters\Emails\EmailMessageSenderTestCase;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

pest()->extends(EmailMessageSenderTestCase::class);

describe(SymfonyMailerMessageSender::class, function () {
    beforeEach(function () {
        $this->sut = new SymfonyMailerMessageSender(
            new BirthdayGreetingEmailMessageComposer(),
            new Mailer(
                Transport::fromDsn("smtp://localhost:{$this->SMTP_PORT}")
            )
        );
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


STR,
            $email['text']
        );
        $this->assertEquals([['address' => 'jane.doe@gmail.com', 'name' => '']], $email['to']);
    });
});
