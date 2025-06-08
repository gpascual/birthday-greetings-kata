<?php

namespace BirthdayGreetings;

final class Employee
{
    private XDate $birthDate;

    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        string $birthDate,
        private readonly string $email
    ) {
        $this->birthDate = new XDate($birthDate);
    }

    public function isBirthday(XDate $today): bool
    {
        return $today->isSameDay($this->birthDate);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function __toString(): string
    {
        return "Employee {$this->firstName} {$this->lastName} <{$this->email}> born {$this->birthDate}";
    }
}
