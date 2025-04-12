<?php

namespace BirthdayGreetings;

interface MessageSender
{
    public function sendMessage(Message $message): void;
}
