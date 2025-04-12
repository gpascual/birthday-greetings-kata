<?php

namespace BirthdayGreetings\Adapters;

use BirthdayGreetings\Message;
use BirthdayGreetings\MessageSender;

class NoOpMessageSender implements MessageSender
{
    public function sendMessage(Message $message): void
    {
        // TODO: Implement sendMessage() method.
    }
}
