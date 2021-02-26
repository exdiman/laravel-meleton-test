<?php

namespace App\RateProviders\BlockchainInfo;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client
{
    /** @var string */
    private $baseUrl = 'https://blockchain.info/';

    /** @var PendingRequest */
    private $http;

    public function __construct()
    {
        $this->http = Http::withOptions([
                'timeout' => 10,
                'connect_timeout' => 5,
            ])->baseUrl($this->baseUrl);
    }

    /**
     * Get ticker
     *
     * @return array[
     *      "USD" => [
     *           "15m" => 50368.65,
     *           "last" => 50368.65,
     *           "buy" => 50368.65,
     *           "sell" => 50368.65,
     *           "symbol" => "$",
     *      ],
     * ]
     * @throws RequestException
     */
    public function getTicker(): array
    {
        return $this->http->get('ticker')->throw()->json();
    }
}
