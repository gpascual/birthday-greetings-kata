<?php

namespace BirthdayGreetings\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessage;
use BirthdayGreetings\Emails\EmailMessageComposer;
use BirthdayGreetings\Emails\EmailMessageSender;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class SymfonyMailerMessageSender extends EmailMessageSender
{
    public function __construct(EmailMessageComposer $emailMessageComposer, private MailerInterface $mailer)
    {
        parent::__construct($emailMessageComposer);
    }

    #[\Override]
    protected function sendEmail(EmailMessage $mailMessage): void
    {
        $email = new Email();
        $email->from($mailMessage->from);
        $email->subject($mailMessage->subject);
        $email->text($mailMessage->body . PHP_EOL);
        $email->to(...$mailMessage->to);

        $this->mailer->send($email);
    }
}
