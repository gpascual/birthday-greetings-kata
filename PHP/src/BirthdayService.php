<?php

namespace BirthdayGreetings;

use Psr\Log\LoggerInterface;

readonly class BirthdayService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private MessageSender $messageSender,
        private LoggerInterface $logger
    ) {
    }

    public function sendGreetings(XDate $xDate): void
    {
        foreach ($this->employeeRepository->findEmployeesCelebratingBirthdayOn($xDate) as $employee) {
            try {
                $this->messageSender->sendMessage(Message::birthdayGreeting($employee));
            } catch (\Exception $e) {
                $this->logger->error("Error processing employee data: " . $e->getMessage(), ['exception' => $e]);
                continue;
            }
        }
    }
}
