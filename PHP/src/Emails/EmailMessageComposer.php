<?php

namespace BirthdayGreetings\Emails;

use BirthdayGreetings\Message;

interface EmailMessageComposer
{
    public function compose(Message $message): EmailMessage;
}
