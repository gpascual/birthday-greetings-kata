<?php

namespace BirthdayGreetings\Adapters;

use BirthdayGreetings\Employee;
use BirthdayGreetings\EmployeeRepository;
use BirthdayGreetings\XDate;
use Rx\Observable;
use function BirthdayGreetings\Functional\filter;
use function BirthdayGreetings\Functional\map;
use function trim;

class CsvEmployeeRepository implements EmployeeRepository
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function findEmployeesCelebratingBirthdayOn(XDate $xDate): Observable
    {
        return Observable::fromIterator($this->extractEmployeeLines())
            ->map(
                fn($data) => new Employee(trim($data[1]), trim($data[0]), trim($data[2]), trim($data[3]))
            )
            ->filter(
                fn(Employee $e) => $e->isBirthday($xDate)
            );
    }

    private function extractEmployeeLines(): \Iterator
    {
        $handler = $this->initializeFileResourceAtStart();

        while (false !== ($data = fgetcsv($handler, 0))) {
            // Skip invalid lines
            if (count($data) < 4) {
                continue;
            }

            yield $data;
        }

        fclose($handler);
    }

    private function initializeFileResourceAtStart()
    {
        $handler = fopen($this->filename, 'r');

        if ($handler === false) {
            throw new \RuntimeException("Could not open file: $this->filename");
        }

        // Skip header
        fgetcsv($handler, 0);

        return $handler;
    }
}
