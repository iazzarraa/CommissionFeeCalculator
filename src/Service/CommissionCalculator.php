<?php

namespace CommissionTask\Service;

use CommissionTask\Model\Operation;
use DateTime;

class CommissionCalculator
{
    private $exchangeRateService;
    private $privateClientWithdrawals = [];
    private $businessClientWithdrawals = [];

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function registerOperation(Operation $operation): void
    {
        $date = $operation->getDate();
        $userType = $operation->getUserType();
        $currency = $operation->getCurrency();
        $userId = $operation->getUserId();
        $amount = $this->convertToEur($operation->getAmount(), $currency);

        if ($userType === 'private') {
            $this->privateClientWithdrawals[$userId][$date->format('Y-m-d')][] = $amount;
        } elseif ($userType === 'business') {
            $this->businessClientWithdrawals[$userId][$date->format('Y-m-d')][] = $amount;
        }
    }

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();
        $currency = $operation->getCurrency();
        $userType = $operation->getUserType();
        $userId = $operation->getUserId();
        $operationType = $operation->getType();
        $date = $operation->getDate();

        if ($operationType === 'deposit') {
            return $this->calculateDepositFee($amount, $currency);
        }

        if ($operationType === 'withdraw') {
            if ($userType === 'private') {
                return $this->calculatePrivateWithdrawFee($userId, $amount, $currency, $date);
            }

            if ($userType === 'business') {
                return $this->calculateBusinessWithdrawFee($amount, $currency);
            }
        }

        return 0.00;
    }

    private function calculateDepositFee(float $amount, string $currency): float
    {
        return $this->roundUp($amount * 0.0003, $currency);
    }

    private function calculatePrivateWithdrawFee(int $userId, float $amount, string $currency, DateTime $date): float
    {
        $amountInEur = $this->convertToEur($amount, $currency);
        $withdrawalsThisWeek = $this->getWeekWithdrawals('private', $userId, $date);
        if ($withdrawalsThisWeek < 1000) {
            $freeAmount = 1000 - $withdrawalsThisWeek;
            $fee = 0;
            if ($amountInEur > $freeAmount) {
                $fee = ($amountInEur - $freeAmount) * 0.003;
            }
            $fee = $this->convertToOperationCurrency($fee,$currency);
            return $this->roundUp($fee, $currency);
        }

        $fee = $this->convertToOperationCurrency($amountInEur * 0.003,$currency);
        return $this->roundUp($fee, $currency);
    }

    private function calculateBusinessWithdrawFee(float $amount,string $currency): float
    {
        return $this->roundUp($amount * 0.005, $currency);
    }

    private function getWeekWithdrawals(string $userType,int $userId, DateTime $date): float
    {
        $startOfWeek = (clone $date)->modify('Monday this week');
        $endOfWeek = (clone $startOfWeek)->modify('Sunday this week');
        $weekWithdrawals = 0;
        if(isset($this->privateClientWithdrawals[$userId]))
        {
            $withdrawals = $userType === 'private' ? $this->privateClientWithdrawals[$userId] : $this->businessClientWithdrawals[$userId];
           
            foreach ($withdrawals as $withdrawalDate => $amounts) {
                if ($withdrawalDate >= $startOfWeek->format('Y-m-d') && $withdrawalDate <= $endOfWeek->format('Y-m-d')) {
                    $weekWithdrawals += array_sum($amounts);
                }
            }
            return $weekWithdrawals;
        }else{
            return 0;
        }
    }

    private function convertToEur(float $amount, string $currency): float
    {
        if ($currency === 'EUR') {
            return $amount;
        }

        $exchangeRate = $this->exchangeRateService->getRate($currency, 'EUR');
        return $amount * $exchangeRate;
    }

    private function convertToOperationCurrency(float $amount, string $currency): float
    {
        if ($currency === 'EUR') {
            return $amount;
        }

        $exchangeRate = $this->exchangeRateService->getRate('EUR', $currency);
        return $amount * $exchangeRate;
    }
    
    private function roundUp(float $amount, string $currency): float
    {
        $precision = match ($currency) {
            'JPY' => 0,
            default => 2,
        };

        $multiplier = pow(10, $precision);

        return ceil($amount * $multiplier) / $multiplier;
    }

}
