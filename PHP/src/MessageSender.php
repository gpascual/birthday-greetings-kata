<?php

namespace BirthdayGreetings;

/**
 * @template M of Message
 */
interface MessageSender
{
    /**
     * @param M $message
     */
    public function sendMessage($message): void;
}
