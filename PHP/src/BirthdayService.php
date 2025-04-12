<?php

namespace BirthdayGreetings;

use BirthdayGreetings\Adapters\CsvEmployeeRepository;
use BirthdayGreetings\Adapters\NoOpEmployeeRepository;
use BirthdayGreetings\Adapters\NoOpMessageSender;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use RuntimeException;

class BirthdayService
{
    private $handler;
    private $smtpHost;
    private $smtpPort;
    private EmployeeRepository $employeeRepository;
    private MessageSender $messageSender;

    public function __construct(EmployeeRepository $employeeRepository, MessageSender $messageSender)
    {
        $this->employeeRepository = $employeeRepository;
        $this->messageSender = $messageSender;
    }

    public static function constructTheUglyWay(string $fileName, string $smtpHost, int $smtpPort): BirthdayService
    {
        $service = new self(new CsvEmployeeRepository($fileName), new NoOpMessageSender());
        $service->smtpHost = $smtpHost;
        $service->smtpPort = $smtpPort;
        return ($service);
    }

    public function sendGreetings(XDate $xDate): void
    {

        foreach ($this->getEmployees() as $employee) {
            try {
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

    /** @return iterable<Employee> */
    private function getEmployees(): iterable
    {
        return $this->employeeRepository->getAll();
    }
}
