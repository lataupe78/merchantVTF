<?php

namespace App\Models;

use App\Models\User;
use App\Models\Asset;
use App\Models\SalePoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'stock',
        'team_id',
        'user_id',
        'asset_id',
        'sale_point_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function sale_point(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id');
    }
}
