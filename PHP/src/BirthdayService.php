<?php

namespace BirthdayGreetings;

use function BirthdayGreetings\Functional\filter;

class BirthdayService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly MessageSender $messageSender
    ) {
    }

    public function sendGreetings(XDate $xDate): void
    {
        $employees = $this->getEmployees();
        $employeesCelebratingBirthday = filter($employees, fn(Employee $e) => $e->isBirthday($xDate));
        foreach ($employeesCelebratingBirthday as $employee) {
            try {
                $this->messageSender->sendMessage(Message::birthdayGreeting($employee));
            } catch (\Exception $e) {
                // Log error and continue with next employee
                error_log("Error processing employee data: " . $e->getMessage());
                continue;
            }
        }
    }


    /** @return iterable<Employee> */
    private function getEmployees(): iterable
    {
        return $this->employeeRepository->getAll();
    }
}
