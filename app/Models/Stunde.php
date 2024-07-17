<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stunde extends Model
{
    use HasFactory;

    public function kurs(): BelongsTo
    {
        return $this->belongsTo(Kurs::class, 'kurs_id');
    }
}
