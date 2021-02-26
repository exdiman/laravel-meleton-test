<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\QueryBuilder;
use App\Http\Resources\Api\v1\RateCollection;
use App\Services\RateService;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilderRequest;

class RatesController extends Controller
{
    /**
     * @param QueryBuilderRequest $request
     * @param RateService $service
     * @return JsonResource
     */
    public function __invoke(QueryBuilderRequest $request, RateService $service): JsonResource
    {
        $query = QueryBuilder::forFakeModel()->allowedFilters(
            AllowedFilter::exact('currency', 'to', false, ',')
        );

        return new RateCollection($service->getRatesWithFee($query));
    }
}
