<?php

namespace App\Models;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssetUnit extends Model
{
    use HasFactory;

    public $fillable =  [
        'name',
        'plural',
        'abbr',
        'quantity',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_asset_unit');
    }
}
