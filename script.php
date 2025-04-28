<?php

namespace CommissionTask;

use CommissionTask\Service\CommissionCalculator;
use CommissionTask\Service\ExchangeRateService;
use CommissionTask\Service\CsvReader;

require 'vendor/autoload.php';

// Path to the CSV file
$csvFile = $argv[1] ?? null;

if (!$csvFile || !file_exists($csvFile)) {
    echo "Usage: php script.php input.csv\n";
    exit(1);
}

$apiKey = '05989a45216aebd33c9b1255'; // https://app.exchangerate-api.com API key
$exchangeService = new ExchangeRateService($apiKey,true);
$calculator = new CommissionCalculator($exchangeService);
$csvReader = new CsvReader($csvFile);

$operations = $csvReader->read();

foreach ($operations as $operation) {
    $fee = $calculator->calculate($operation);
    if ($operation->getType() === 'withdraw') {
        $calculator->registerOperation($operation); // Register Operations
    }
    echo number_format($fee, 2, '.', '') . "\n";
}
