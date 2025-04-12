<?php

namespace BirthdayGreetings\Adapters;

use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;

class CsvEmployeeRepository implements EmployeeRepository
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }


    public function getAll(): iterable
    {
        $handler = fopen($this->filename, 'r');
        if ($handler === false) {
            throw new \RuntimeException("Could not open file: $this->filename");
        }
        // Skip header
        fgetcsv($handler, 0, ',', '"', '\\');

        while (($data = fgetcsv($handler, 0, ',', '"', '\\')) !== false) {
            if (count($data) < 4) {
                continue; // Skip invalid lines
            }

            yield new Employee(trim($data[1]), trim($data[0]), trim($data[2]), trim($data[3]));
        }

        fclose($handler);
    }
}
