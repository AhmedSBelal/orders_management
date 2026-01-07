<?php

namespace App\Models;

use App\Http\Requests\Colors\ColorFilterRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

class Color extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'photo',
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }


    static public function colorsByFilter(ColorFilterRequest $request) {

        $colors = self::query();

        if (!empty($request->name)) {
            $colors->where('name', 'like', '%' . $request->name . '%');
        }

        return $colors;

    }


}
