<?php

namespace BirthdayGreetings\Emails;

final readonly class EmailMessage
{
    public function __construct(
        public string $from,
        public array $to,
        public string $subject,
        public string $body
    ) {
    }
}
