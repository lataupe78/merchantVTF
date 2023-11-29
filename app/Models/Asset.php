<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\AssetUnit;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asset extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'assets';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',

        'quantity_unit',
        'asset_unit_id',

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'quantity_unit' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            self::created(function ($model) {

                // Stock::create([
                //     'asset_id' => $model->id,
                //     'sale_point_id' => null,
                //     'current_stock' => 0,
                //     'danger_level' => 0,
                // ]);
            });

            // static::addGlobalScope('transaction_team_id', function (Builder $builder) {
            //     $builder->where('team_id', auth()->user()->current_team_id);
            // });
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'asset_id');
    }


    public function unit(): BelongsTo
    {
        return $this->belongsTo(AssetUnit::class, 'asset_unit_id');
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(AssetUnit::class, 'asset_asset_unit');
    }
}
