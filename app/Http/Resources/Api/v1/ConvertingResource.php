<?php

namespace App\Http\Resources\Api\v1;

use App\Domain\Converting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConvertingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var $this Converting */
        return [
            'currency_from' =>  $this->currency_from,
	        'currency_to' =>  $this->currency_to,
	        'value' => $this->value,
	        'converted_value' => $this->converted_value,
	        'rate' =>  $this->rate,
	        'created_at' => $this->created_at->timestamp,
        ];
    }
}
