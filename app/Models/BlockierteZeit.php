<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockierteZeit extends Model
{
    use HasFactory;

    public function dozent(): BelongsTo
    {
        return $this->belongsTo(Dozent::class, 'doz_id');
    }
}
