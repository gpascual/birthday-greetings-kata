<?php

namespace BirthdayGreetings;

readonly class BirthdayService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private MessageSender      $messageSender
    ) {
    }

    public function sendGreetings(XDate $xDate): void
    {
        foreach ($this->employeeRepository->findEmployeesCelebratingBirthdayOn($xDate) as $employee) {
            try {
                $this->messageSender->sendMessage(Message::birthdayGreeting($employee));
            } catch (\Exception $e) {
                // Log error and continue with next employee
                error_log("Error processing employee data: " . $e->getMessage());
                continue;
            }
        }
    }
}
