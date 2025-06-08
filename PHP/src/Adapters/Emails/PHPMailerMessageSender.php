<?php

namespace BirthdayGreetings\Adapters\Emails;

use BirthdayGreetings\Emails\EmailMessage;
use BirthdayGreetings\Emails\EmailMessageComposer;
use BirthdayGreetings\Emails\EmailMessageSender;
use BirthdayGreetings\Message;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @template M of Message
 * @extends EmailMessageSender<M>
 */
final readonly class PHPMailerMessageSender extends EmailMessageSender
{
    /**
     * @param EmailMessageComposer<M> $emailMessageComposer
     */
    public function __construct(
        EmailMessageComposer $emailMessageComposer,
        private string $smtpHost,
        private int $smtpPort
    ) {
        parent::__construct($emailMessageComposer);
    }

    #[\Override]
    protected function sendEmail(EmailMessage $emailMessage): void
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->Port = $this->smtpPort;
            $mail->SMTPAuth = false;

            // Recipients
            $mail->setFrom($emailMessage->from);
            $mail->addAddress($emailMessage->to[0]);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $emailMessage->subject;
            $mail->Body = $emailMessage->body;

            $mail->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 0, $e);
        }
    }
}
