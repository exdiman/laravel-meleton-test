<?php

namespace App\Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Converting
 *
 * @property int $id
 * @property string $currency_from
 * @property string $currency_to
 * @property float value
 * @property float converted_value
 * @property float rate
 * @property Carbon|null $created_at
 *
 * @package App\Domain
 */
class Converting extends Model
{
    /** @inheritdoc */
    public $timestamps = false;

    /** @inheritdoc */
    protected $guarded = [];

    /** @inheritdoc */
    protected $dates = [
        'created_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'value' => 'double',
        'converted_value' => 'double',
        'rate' => 'double',
    ];

    /** @inheritdoc */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
