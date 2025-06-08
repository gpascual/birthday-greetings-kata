<?php

namespace BirthdayGreetings\Emails;

use BirthdayGreetings\Message;

/** @template M of Message */
interface EmailMessageComposer
{
    /**
     * @param M $message
     */
    public function compose($message): EmailMessage;
}
