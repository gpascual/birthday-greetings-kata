<?php

namespace BirthdayGreetings\Emails;

use BirthdayGreetings\BirthdayGreetingMessage;

/**
 * @implements EmailMessageComposer<BirthdayGreetingMessage>
 */
final class BirthdayGreetingEmailMessageComposer implements EmailMessageComposer
{
    #[\Override]
    public function compose($message): EmailMessage
    {
        return new EmailMessage(
            'sender@here.com',
            [$message->employee->getEmail()],
            'Happy Birthday!',
            str_replace('%NAME%', $message->employee->getFirstName(), 'Happy Birthday, dear %NAME%!')
        );
    }
}
