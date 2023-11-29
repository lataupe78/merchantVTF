<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    public $fillable =  [
        'title',
        'lat',
        'lng',
        'altitude',
        'accuracy',
        'altitude_accuracy',
    ];
}
