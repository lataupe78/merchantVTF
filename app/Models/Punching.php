<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Punching extends Model
{
    use HasFactory;
    public $fillable = [
        'worker_id',
        'status',
        'ip', 'device_infos',
        'lat', 'lng', 'altitude',
        'accuracy', 'altitude_accuracy',
        'punched_at', 'current_date',
    ];



    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
