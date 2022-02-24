<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class LearnList extends Model
{
    use SoftDeletes;

    //fillやfirstOrCreateをコントローラで使う際は必須
    protected $fillable = [
        'name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function cards(): HasMany
    {
        return $this->hasMany('App\LearnCard', 'list_id', 'id');
    }
}
