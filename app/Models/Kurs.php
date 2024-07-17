<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurs extends Model
{
    use HasFactory;

    public function studiengang(): BelongsTo
    {
        return $this->belongsTo(Studiengang::class, 'stdg_id');
    }

    public function dozent(): BelongsTo
    {
        return $this->belongsTo(Dozent::class, 'doz_id');
    }

    public function stunden(): HasMany
    {
        return $this->hasMany(Stunde::class);
    }
}
