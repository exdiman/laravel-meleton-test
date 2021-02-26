<?php

namespace App\Http\Resources\Api\v1;

use App\Domain\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->mapWithKeys(function (Rate $rate) {
            return [$rate->to => $rate->rate];
        })->all();
    }
}
