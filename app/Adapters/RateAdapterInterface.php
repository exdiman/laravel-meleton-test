<?php

namespace App\Adapters;

use App\Domain\Rate;
use Illuminate\Support\Collection;

interface RateAdapterInterface
{
    /**
     * @return Collection|Rate[]
     */
    public function gateRates(): Collection;
}
