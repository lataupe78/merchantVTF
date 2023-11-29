<?php

namespace App\Models;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePointUser extends Pivot
{
  use LogsActivity;

  protected $table = 'sale_point_user';

  public $incrementing = true; // To log subject_id on pivot tables
  
  protected $fillable = [
    'sale_point_id',
    'user_id',
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['sale_point_id', 'user_id'])
      ->logOnlyDirty(true)
      ->submitEmptyLogs(false);
  }


  public function sale_point(): BelongsTo
  {
    return $this->belongsTo(SalePoint::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
