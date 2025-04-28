<?php

namespace CommissionTask\Service;

use CommissionTask\Model\Operation;
use DateTime;

class CsvReader
{
    private $csvFile;

    public function __construct(string $csvFile)
    {
        $this->csvFile = $csvFile;
    }

    public function read(): array
    {
        $operations = [];
        if (($handle = fopen($this->csvFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $date = DateTime::createFromFormat('Y-m-d', $data[0]);
                $userId = (int)$data[1];
                $userType = $data[2];
                $type = $data[3];
                $amount = (float)$data[4];
                $currency = $data[5];

                $operations[] = new Operation($date, $userId, $userType, $type, $amount, $currency);
            }
            fclose($handle);
        }

        return $operations;
    }
}
