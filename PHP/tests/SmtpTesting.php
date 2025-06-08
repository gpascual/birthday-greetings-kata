<?php

namespace BirthdayGreetings\Tests;

trait SmtpTesting
{
    // MailDev's default SMTP port
    protected const int SMTP_PORT = 1025;
    protected const string API_URL = 'http://localhost:1080';

    protected function deleteAllEmails(): void
    {
        file_get_contents(self::API_URL . '/email/all', false, stream_context_create([
            'http' => ['method' => 'DELETE']
        ]));
    }

    protected function getEmails(): array
    {
        $response = file_get_contents(self::API_URL . '/email');
        return json_decode($response, true) ?? [];
    }
}
