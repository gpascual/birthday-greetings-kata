<?php

namespace BirthdayGreetings;

abstract class EmailMessageSender implements MessageSender
{
    public function sendMessage(Message $message): void
    {
        // TODO: Implement sendMessage() method.
    }
}
