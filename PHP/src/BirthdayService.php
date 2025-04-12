<?php

namespace BirthdayGreetings;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use RuntimeException;

class BirthdayService
{
    private $handler;
    private $smtpHost;
    private $smtpPort;

    public static function constructTheUglyWay(string $fileName, string $smtpHost, int $smtpPort): BirthdayService
    {
        $service = new self();
        $service->handler = fopen($fileName, 'r');
        $service->smtpHost = $smtpHost;
        $service->smtpPort = $smtpPort;
        if ($service->handler === false) {
            throw new RuntimeException("Could not open file: $fileName");
        }
        return ($service);
    }

    public function sendGreetings(XDate $xDate): void
    {
// Skip header
        fgetcsv($this->handler, 0, ',', '"', '\\');

        while (($data = fgetcsv($this->handler, 0, ',', '"', '\\')) !== false) {
            if (count($data) < 4) {
                continue; // Skip invalid lines
            }

            try {
                $employee = new Employee(trim($data[1]), trim($data[0]), trim($data[2]), trim($data[3]));
                if ($employee->isBirthday($xDate)) {
                    $recipient = $employee->getEmail();
                    $body = str_replace('%NAME%', $employee->getFirstName(), 'Happy Birthday, dear %NAME%');
                    $subject = 'Happy Birthday!';
                    $this->sendMessage('sender@here.com', $subject, $body, $recipient);
                }
            } catch (\Exception $e) {
                // Log error and continue with next employee
                error_log("Error processing employee data: " . $e->getMessage());
                continue;
            }
        }

        fclose($this->handler);
    }

    private function sendMessage(string $sender, string $subject, string $body, string $recipient): void
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
            throw new RuntimeException("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 0, $e);
        }
    }
}
