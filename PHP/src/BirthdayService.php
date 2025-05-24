<?php

namespace BirthdayGreetings;

use Psr\Log\LoggerInterface;
use Rx\Observable;

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
        Observable::fromIterator($this->employeeRepository->findEmployeesCelebratingBirthdayOn($xDate))
            ->map(Message::birthdayGreeting(...))
            ->subscribe($this->sendGreeting(...));
    }

    private function sendGreeting(Message $greeting): void
    {
        Observable::of($greeting)
            ->subscribe(
                $this->messageSender->sendMessage(...),
                $this->logSendingError(...)
            );
    }

    private function logSendingError(\Throwable $e): void
    {
        $this->logger->error(
            "Error processing employee data: " . $e->getMessage(),
            ['exception' => $e]
        );
    }
}
