<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Testing\Fluent\Concerns\Has;

class Color extends Model
{

    use HasFactory;
    protected $fillable = [
        'name'
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
