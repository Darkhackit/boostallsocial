<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
    public function social_media(): BelongsTo
    {
        return $this->belongsTo(SocialMedia::class);
    }
    public function social_media_orders(): HasMany
    {
        return $this->hasMany(SocialMediaOrder::class);
    }

}
