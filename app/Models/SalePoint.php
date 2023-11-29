<?php

namespace App\Models;

use App\Models\User;
use App\Models\SalePointUser;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePoint extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'is_active',
        'name',
        'description',
        'address',
        'city',
        'latitude',
        'longitude',
    ];


    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty(true)
            ->submitEmptyLogs(false);
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sale_point_user')
            ->where('users.is_active', true)
            ->using(SalePointUser::class);
    }
}
