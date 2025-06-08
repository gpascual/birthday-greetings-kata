<?php

namespace BirthdayGreetings\Emails;

use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;

abstract readonly class EmailMessageSender implements MessageSender
{
    public function __construct(protected EmailMessageComposer $emailMessageComposer)
    {
    }

    abstract protected function sendEmail(EmailMessage $mailMessage): void;

    #[\Override]
    final public function sendMessage(Message $message): void
    {
        $this->sendEmail(
            $this->emailMessageComposer->compose($message)
        );
    }
}
