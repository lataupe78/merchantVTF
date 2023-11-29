<?php

namespace App\Models;

use App\Casts\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    public $fillable = [
        'date',
        'seller_id',
        'author_id',
        'sale_point_id',
        'cash',
        'cash_reel',
        'card',
        'card_reel',
        'comment',
        'is_validated',

    ];

    protected $casts = [
        'date' => 'date',
        'is_validated' => 'boolean',
        'cash' => Money::class,
        'cash_reel' => Money::class,
        'card' => Money::class,
        'card_reel' => Money::class,

        'total_cash' => Money::class,
        'total_cash_reel' => Money::class,
        'total_card' => Money::class,
        'total_card_reel' => Money::class,
        'total' => Money::class,
        'total_reel' => Money::class,
    ];

    // protected function cash(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($price) => $price / 100,
    //         set: fn ($price) => $price * 100,
    //     );
    // }

    // protected function card(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($price) => $price / 100,
    //         set: fn ($price) => $price * 100,
    //     );
    // }

    // protected function cashReel(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($price) => $price / 100,
    //         set: fn ($price) => $price * 100,
    //     );
    // }

    // protected function cardReel(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($price) => $price / 100,
    //         set: fn ($price) => $price * 100,
    //     );
    // }


    //     public function getTotalReelAttribute()
    // {
    //     return new MoneyCast($this->transactions()->sum('value'), 'currency', 0);
    // }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function sale_point(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id');
    }
}
