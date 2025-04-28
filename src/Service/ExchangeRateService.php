<?php

namespace CommissionTask\Service;

use GuzzleHttp\Client;
use PhpParser\Node\Expr\Cast\Array_;

class ExchangeRateService
{
    private $apiKey;
    private $client;
    private $useDefaultRate;
    private $defaultRates = Array('EUR' => [
                                        'USD' => 1.1497,
                                        'JPY' => 129.53
                                    ],
                                'USD' => ['EUR' => 0.86979212],
                                'JPY' => ['EUR' => 0.007720219],
                                );

    public function __construct(string $apiKey, bool $useDefaultRate = false)
    {
        $this->apiKey = $apiKey;
        $this->useDefaultRate = $useDefaultRate;
        $this->client = new Client();
    }

    public function getRate(string $from, string $to): float
    {
        if($this->useDefaultRate){
            return $this->defaultRates[$from][$to];
        }
        
        $response = $this->client->get("https://v6.exchangerate-api.com/v6/{$this->apiKey}/pair/{$from}/{$to}");
        $data = json_decode($response->getBody(), true);
        return $data['conversion_rate'] ?? 1;
    }
}
