<?php

namespace App\Models;

use App\Models\SalePoint;
use App\Models\UserInvitation;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lab404\Impersonate\Services\ImpersonateManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use CausesActivity;
    use LogsActivity;
    use Impersonate;

    protected $fillable = [
        'is_active',
        'name',
        'email',
        'phone',
        'password',
        'last_connected_at'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_connected_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function impersonate(User $user, $guardName = 'web')
    {
        return app(ImpersonateManager::class)->take($this, $user, $guardName);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'roles'])
            ->logOnlyDirty(true)
            ->submitEmptyLogs(false);
    }


    public function sale_points(): BelongsToMany
    {
        return $this->belongsToMany(SalePoint::class, 'sale_point_user');
    }

    public function working_ranges(): HasMany
    {
        return $this->hasMany(WorkingRange::class, 'worker_id');
    }


    public function latest_working_range()
    {
        return $this->hasOne(WorkingRange::class, 'worker_id')->latest('current_date');
    }

    public function latest_validated_working_range()
    {
        return $this->hasOne(WorkingRange::class, 'worker_id')
            ->whereNotNull('validated_started_at')
            ->latest('current_date');
    }


    public function punchings(): HasMany
    {
        return $this->hasMany(Punching::class, 'worker_id');
    }

    public function latest_punching()
    {
        return $this->hasOne(Punching::class, 'worker_id')->latest('current_date');
    }


    public function invitation(): HasOne
    {
        return $this->hasOne(UserInvitation::class, 'user_id');
    }
}
