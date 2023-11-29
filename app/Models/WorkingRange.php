<?php

namespace App\Models;

use App\Casts\CustomDatetime;
use Illuminate\Database\Eloquent\Model;
use App\QueryBuilders\WorkingRangeBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkingRange extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'comment',
        'status',
        'worker_id',
        'sale_point_id',
        'current_date',
        'starts_at',
        'ends_at',
        'started_at',
        'ended_at',
        'validated_started_at',
        'validated_ended_at',
        'validated_at'
    ];



    protected $casts = [
        // 'current_date' => 'date:Y-m-d',
        'starts_at' => CustomDatetime::class,
        'ends_at' => CustomDatetime::class,
        'started_at' => CustomDatetime::class,
        'ended_at' => CustomDatetime::class,
        'validated_started_at' => CustomDatetime::class,
        'validated_ended_at' => CustomDatetime::class,
        'validated_at' => 'datetime',
    ];

    public function newEloquentBuilder($query): WorkingRangeBuilder
    {
        return new WorkingRangeBuilder($query);
    }


    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function sale_point(): BelongsTo
    {
        return $this->belongsTo(SalePoint::class, 'sale_point_id');
    }

    // public function startsAt(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) =>   [
    //             'date' => date('Y-m-d', strtotime($value)),
    //             'time' => date('H:i:s', strtotime($value)),
    //         ],
    //         set: fn ($value) =>  $value['date'] . ' ' . $value['time']
    //     );
    // }
}
