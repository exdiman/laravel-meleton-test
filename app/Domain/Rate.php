<?php

namespace App\Domain;

class Rate
{
    /** @var string */
    public $from;

    /** @var string */
    public $to;

    /** @var float */
    public $rate;

    /** @var int */
    public $precision;

    /**
     * Rate constructor.
     *
     * @param  string  $from
     * @param  string  $to
     * @param  float  $rate
     * @param  int  $precision
     */
    public function __construct(string $from, string $to, float $rate, int $precision = 2)
    {
        $this->from = strtoupper($from);
        $this->to = strtoupper($to);
        $this->rate = $rate;
        $this->precision = $precision;
    }

    /**
     * @param  string  $from
     * @param  string  $to
     * @param  float  $rate
     * @param  int|null  $precision
     * @return static
     */
    public static function make(string $from, string $to, float $rate, ?int $precision): self
    {
        return new static($from, $to, $rate, $precision);
    }
}
