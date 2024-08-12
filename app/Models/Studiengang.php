<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Studiengang extends Model
{
    use HasFactory;

    protected $table = 'studiengänge';

    protected $fillable = [
        'stdg_name',
        'stdg_kürzel',
    ];

    public function kurse(): HasMany
    {
        return $this->hasMany(Kurs::class, 'stdg_id');
    }
}
