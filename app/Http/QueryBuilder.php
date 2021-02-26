<?php

namespace App\Http;

use App\FakeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QueryBuilder extends \Spatie\QueryBuilder\QueryBuilder
{
    /**
     * @param  Request|null  $request
     * @return QueryBuilder
     */
    public static function forFakeModel(?Request $request = null): self
    {
        return parent::for(FakeModel::query(), $request);
    }

    /**
     * @param  Collection  $items
     * @return Collection
     */
    public function applyCollectionWheres(Collection $items): Collection
    {
        $query = $this->getQuery();

        foreach ($query->wheres as $where) {
            $field = Str::after($where['column'], $query->from . '.');
            switch ($where['type']) {
                case 'In' :
                    $items = $items->whereIn($field, $where['values']);
                    break;
                case 'Basic' :
                    $items = $items->where($field, $where['operator'], $where['value']);
                    break;
            }
        }

        return $items;
    }
}
