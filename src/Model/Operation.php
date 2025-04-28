<?php

namespace CommissionTask\Model;

use DateTime;

class Operation
{
    private $date;
    private $userId;
    private $userType;
    private $type;
    private $amount;
    private $currency;

    public function __construct(DateTime $date, int $userId, string $userType, string $type, float $amount, string $currency)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->type = $type;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
