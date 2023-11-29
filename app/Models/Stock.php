<?php

namespace App\Models;

use App\Models\Asset;
use App\Models\SalePoint;
use App\QueryBuilders\StockBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'sale_point_id',
        'asset_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'current_stock',
        'danger_level',
    ];

    public function newEloquentBuilder($query): StockBuilder
    {
        return new StockBuilder($query);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id')
            ->with('unit');
    }

    public function sale_point(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id');
    }
}
