<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dozent extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kurse(): HasMany
    {
        return $this->hasMany(Kurs::class);
    }

    public function blockierteZeiten(): HasMany
    {
        return $this->hasMany(BlockierteZeit::class);
    }
}
