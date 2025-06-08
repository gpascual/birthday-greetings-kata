<?php

namespace BirthdayGreetings\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessage;
use BirthdayGreetings\Emails\EmailMessageComposer;
use BirthdayGreetings\Emails\EmailMessageSender;
use Symfony\Component\Mailer\MailerInterface;

readonly class SymfonyMailerMessageSender extends EmailMessageSender
{
    public function __construct(EmailMessageComposer $emailMessageComposer, private MailerInterface $mailer)
    {
        parent::__construct($emailMessageComposer);
    }

    protected function sendEmail(EmailMessage $mailMessage): void
    {
        // TODO: Implement sendEmail() method.
    }
}
