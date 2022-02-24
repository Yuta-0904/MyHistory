<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearnCard extends Model
{
    use SoftDeletes;
    //fillやfirstOrCreateをコントローラで使う際は必須
    protected $fillable = [
        'name','content','status','list_id','user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo('App\LearnList');
    }
}
