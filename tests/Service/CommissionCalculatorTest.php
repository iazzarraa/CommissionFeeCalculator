<?php

use PHPUnit\Framework\TestCase;
use CommissionTask\Service\CommissionCalculator;
use CommissionTask\Service\ExchangeRateService;
use CommissionTask\Model\Operation;

class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $calculator;

    protected function setUp(): void
    {
        $apiKey = '05989a45216aebd33c9b1255'; 
        $exchangeRateService = new ExchangeRateService($apiKey, TRUE);
        $this->calculator = new CommissionCalculator($exchangeRateService);
    }

    public function testCommissionCalculation(): void
    {
        $input = [
            ["2014-12-31", "4", "private", "withdraw", "1200.00", "EUR"],
            ["2015-01-01", "4", "private", "withdraw", "1000.00", "EUR"],
            ["2016-01-05", "4", "private", "withdraw", "1000.00", "EUR"],
            ["2016-01-05", "1", "private", "deposit", "200.00", "EUR"],
            ["2016-01-06", "2", "business", "withdraw", "300.00", "EUR"],
            ["2016-01-06", "1", "private", "withdraw", "30000", "JPY"],
            ["2016-01-07", "1", "private", "withdraw", "1000.00", "EUR"],
            ["2016-01-07", "1", "private", "withdraw", "100.00", "USD"],
            ["2016-01-10", "1", "private", "withdraw", "100.00", "EUR"],
            ["2016-01-10", "2", "business", "deposit", "10000.00", "EUR"],
            ["2016-01-10", "3", "private", "withdraw", "1000.00", "EUR"],
            ["2016-02-15", "1", "private", "withdraw", "300.00", "EUR"],
            ["2016-02-19", "5", "private", "withdraw", "3000000", "JPY"],
        ];

        $expectedResults = [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0.00,
            1.3,
            0.31,
            0.30,
            3.00,
            0.00,
            0.00,
            8612.00,
        ];

        $actualResults = [];

        foreach ($input as $row) {
            $operation = new Operation(
                new DateTime($row[0]),
                (int) $row[1],
                $row[2],
                $row[3],
                (float) $row[4],
                $row[5]
            );

            $fee = $this->calculator->calculate($operation);
            $this->calculator->registerOperation($operation);
            $actualResults[] = round($fee, 2);
        }

        $this->assertEquals($expectedResults, $actualResults);
    }
}
