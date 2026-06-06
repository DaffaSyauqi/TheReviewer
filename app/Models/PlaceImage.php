<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceImage extends Model
{
    protected $fillable = [
        'place_id',
        'disk',
        'path',
        'mime_type',
        'size',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
