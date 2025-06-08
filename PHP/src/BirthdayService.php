<?php

namespace BirthdayGreetings;

use Psr\Log\LoggerInterface;
use Rx\Observable;

final readonly class BirthdayService
{
    /**
     * @param MessageSender<BirthdayGreetingMessage> $messageSender
     */
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private MessageSender $messageSender,
        private LoggerInterface $logger
    ) {
    }

    public function sendGreetings(XDate $xDate): void
    {
        $this->employeeRepository->findEmployeesCelebratingBirthdayOn($xDate)
            ->map(BirthdayGreetingMessage::create(...))
            ->doOnError($this->logSendingError(...))
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
