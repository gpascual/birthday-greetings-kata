<?php

namespace BirthdayGreetings\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessage;
use BirthdayGreetings\Emails\EmailMessageComposer;
use BirthdayGreetings\Emails\EmailMessageSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final readonly class PHPMailerMessageSender extends EmailMessageSender
{
    public function __construct(
        EmailMessageComposer $emailMessageComposer,
        private readonly string $smtpHost,
        private readonly int $smtpPort
    ) {
        parent::__construct($emailMessageComposer);
    }

    #[\Override]
    protected function sendEmail(EmailMessage $mailMessage): void
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->Port = $this->smtpPort;
            $mail->SMTPAuth = false;

            // Recipients
            $mail->setFrom($mailMessage->from);
            $mail->addAddress($mailMessage->to[0]);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $mailMessage->subject;
            $mail->Body = $mailMessage->body;

            $mail->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 0, $e);
        }
    }
}
