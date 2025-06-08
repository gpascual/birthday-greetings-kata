<?php

use BirthdayGreetings\BirthdayGreetingMessage;
use BirthdayGreetings\Emails\EmailMessageSender;
use BirthdayGreetings\Employee;
use BirthdayGreetings\Tests\Unit\Adapters\Emails\EmailMessageSenderTestCase;

pest()->extends(EmailMessageSenderTestCase::class);

describe(EmailMessageSender::class, function () {
    afterEach(function () {
        $this->deleteAllEmails();
    });

    it('sends a message', function (EmailMessageSender $sut) {
        $sut->sendMessage(
            BirthdayGreetingMessage::create(new Employee('Jane', 'Doe', '2000/01/01', 'jane.doe@gmail.com'))
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
    })->with('adapters');
});
