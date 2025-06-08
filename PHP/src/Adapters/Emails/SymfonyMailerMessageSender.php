<?php

namespace BirthdayGreetings\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessage;
use BirthdayGreetings\Emails\EmailMessageComposer;
use BirthdayGreetings\Emails\EmailMessageSender;
use BirthdayGreetings\Message;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @template M of Message
 * @extends EmailMessageSender<M>
 */
final readonly class SymfonyMailerMessageSender extends EmailMessageSender
{
    /**
     * @param EmailMessageComposer<M> $emailMessageComposer
     */
    public function __construct(EmailMessageComposer $emailMessageComposer, private MailerInterface $mailer)
    {
        parent::__construct($emailMessageComposer);
    }

    #[\Override]
    protected function sendEmail(EmailMessage $emailMessage): void
    {
        $this->mailer->send(
            new Email()
                ->from($emailMessage->from)
                ->subject($emailMessage->subject)
                ->text($emailMessage->body . PHP_EOL)
                ->to(...$emailMessage->to)
        );
    }
}
