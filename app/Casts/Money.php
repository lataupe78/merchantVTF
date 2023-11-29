<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Money implements CastsAttributes
{

    // protected $amount;
    // protected $currency;

    /**
     * Money constructor.
     * @param $amount
     * @param $currency
     */
    // public function __construct($amount, $currency = '€')
    // {
    //     $this->amount = $amount;
    //     $this->currency = $currency;
    // }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value / 100;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value * 100;
    }
}
