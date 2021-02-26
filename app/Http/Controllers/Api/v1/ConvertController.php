<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\NoRateFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ConvertingResource;
use App\Services\RateService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class ConvertController extends Controller
{
    /**
     * @param  Request  $request
     * @param  RateService  $service
     * @return JsonResource
     * @throws ValidationException
     */
    public function __invoke(Request $request, RateService $service): JsonResource
    {
        $validated = $request->validate([
            'currency_from' => 'required',
            'currency_to' => 'required',
            // TODO для каждой currency_from должно быть свое минимальное значение, по условиям задания - мимниум 0.01
            'value' => 'required|numeric|min:0.01',
        ]);

        try {
            $converting = $service->convertMoney(
                $validated['currency_from'],
                $validated['currency_to'],
                $validated['value'],
            );
        } catch (NoRateFoundException $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }

        return new ConvertingResource($converting);
    }
}
