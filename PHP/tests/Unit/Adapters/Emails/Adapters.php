<?php

use BirthdayGreetings\Adapters\Emails\PHPMailerMessageSender;
use BirthdayGreetings\Adapters\Emails\SymfonyMailerMessageSender;
use BirthdayGreetings\Emails\BirthdayGreetingEmailMessageComposer;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

dataset('adapters', [
    'PHPMailer' => [
        new PHPMailerMessageSender(
            new BirthdayGreetingEmailMessageComposer(),
            'localhost',
            1025
        ),
    ],
    'Symfony\Mailer' => [
        new SymfonyMailerMessageSender(
            new BirthdayGreetingEmailMessageComposer(),
            new Mailer(
                Transport::fromDsn('smtp://localhost:1025')
            )
        )
    ]
]);
