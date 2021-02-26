<?php

namespace App\Services;

use App\Adapters\RateAdapterInterface;
use App\Domain\Converting;
use App\Domain\Rate;
use App\Exceptions\NoRateFoundException;
use App\Http\QueryBuilder;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Support\Collection;

class RateService
{
    /** @var RateAdapterInterface */
    private $adapter;

    /** @var float */
    private $feePercent;

    public function __construct(RateAdapterInterface $adapter, float $feePercent)
    {
        $this->adapter = $adapter;
        $this->feePercent = $feePercent;
    }

    /**
     * @param  QueryBuilder  $query
     * @return Collection|Rate[]
     */
    public function getRatesWithFee(QueryBuilder $query): Collection
    {
        $rates = $this->adapter->gateRates();

        $rates = $query->applyCollectionWheres($rates);

        return $rates->map(function (Rate $rate) {
                return $this->applyFee($rate);
            })->sortBy(function (Rate $rate) {
                return $rate->rate;
            });
    }

    /**
     * @param string $from
     * @param string $to
     * @param float $value
     * @return Converting
     */
    public function convertMoney(string $from, string $to, float $value): Converting
    {
        $rate = $this->getRate($from, $to);

        if (! $rate) {
            throw new NoRateFoundException('Invalid currencies. No rate found for given currencies.');
        }

        $rate = $this->applyFee($rate);

        $converting = new Converting([
            'currency_from' => $from,
            'currency_to' => $to,
            'value' => $value,
            'converted_value' => BigDecimal::of($value)->multipliedBy($rate->rate)
                ->toScale($rate->precision, RoundingMode::FLOOR)
                ->toFloat(),
            'rate' => $rate->rate,
        ]);

        $converting->save();

        return $converting;
    }

    /**
     * Рассчет курса с учетом комиссии (по условию задания помещено в сервис)
     *
     * @param Rate $rate
     * @return Rate
     */
    protected function applyFee(Rate $rate): Rate
    {
        $rate->rate = BigDecimal::of(100)
            ->minus($this->feePercent)
            ->multipliedBy($rate->rate)
            ->dividedBy(100, $rate->precision, RoundingMode::FLOOR)
            ->toFloat();

        return $rate;
    }

    /**
     * @param string $from
     * @param string $to
     * @return Rate|null
     */
    protected function getRate(string $from, string $to): ?Rate
    {
        $rates = $this->adapter->gateRates();

        $directRate = $rates->first(function (Rate $rate) use ($from, $to) {
            return $rate->from === $from && $rate->to === $to;
        });

        if ($directRate) {
            return $directRate;
        }

        $reverseRate = $rates->first(function (Rate $rate) use ($from, $to) {
            return $rate->from === $to && $rate->to === $from;
        });

        if ($reverseRate) {
            // TODO определять precision для каждой валюты
            $precision = 10;
            $rate = BigDecimal::of(1)
                ->dividedBy($reverseRate->rate, $precision, RoundingMode::FLOOR)
                ->toFloat();

            return Rate::make($from, $to, $rate, $precision);
        }

        return null;
    }
}
