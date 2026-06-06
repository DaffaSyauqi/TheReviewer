<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PlaceImage extends Model
{
    protected $fillable = [
        'place_id',
        'disk',
        'path',
        'mime_type',
        'size',
    ];

    protected $appends = ['url'];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => Storage::disk($this->disk)->url($this->path));
    }
}
