<?php

namespace BirthdayGreetings\Emails;

use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;

/**
 * @template M of Message
 * @implements MessageSender<M>
 */
abstract readonly class EmailMessageSender implements MessageSender
{
    /**
     * @param EmailMessageComposer<M> $emailMessageComposer
     */
    public function __construct(protected EmailMessageComposer $emailMessageComposer)
    {
    }

    abstract protected function sendEmail(EmailMessage $emailMessage): void;

    #[\Override]
    final public function sendMessage($message): void
    {
        $this->sendEmail(
            $this->emailMessageComposer->compose($message)
        );
    }
}
