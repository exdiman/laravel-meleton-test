<?php

namespace App\Adapters;

use App\Domain\Rate;
use App\RateProviders\BlockchainInfo\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class BlockchainInfoRateAdapter implements RateAdapterInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Collection|Rate[]
     * @throws RequestException
     */
    public function gateRates(): Collection
    {
        try {
            $ticker = $this->client->getTicker();
        } catch (RequestException $e) {
            // TODO обработка ошибок client
            throw $e;
        }

        return collect($ticker)->map(function (array $item, string $currency) {
                // TODO определять precision для каждой валюты, precision = 2, так как фиатные валюты
                return Rate::make('BTC', $currency,  (float) $item['last'], 2);
            })->values();
    }
}
