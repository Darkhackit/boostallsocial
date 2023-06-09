<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialMedia extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::created(function ($model) {

        });
    }

    public function countries() :HasMany
    {
        return $this->hasMany(Country::class);
    }
}
