<?php

namespace App\Models;



use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class ActivityLog extends BaseActivity
{
    use HasFactory;



    // use Filterable;

    public $fillable = ['*'];
}
