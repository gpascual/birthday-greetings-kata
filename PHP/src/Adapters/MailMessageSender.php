<?php

namespace BirthdayGreetings\Adapters;

use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailMessageSender implements MessageSender
{
    public function __construct(private string $smtpHost, private int $smtpPort)
    {
    }

    public function sendMessage(Message $message): void
    {
        $recipient = $message->employee->getEmail();
        $body = str_replace('%NAME%', $message->employee->getFirstName(), 'Happy Birthday, dear %NAME%');
        $subject = 'Happy Birthday!';
        $this->sendEmail('sender@here.com', $subject, $body, $recipient);
    }

    private function sendEmail(string $sender, string $subject, string $body, string $recipient): void
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->Port = $this->smtpPort;
            $mail->SMTPAuth = false;

            // Recipients
            $mail->setFrom($sender);
            $mail->addAddress($recipient);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 0, $e);
        }
    }
}
