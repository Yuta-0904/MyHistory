<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TaskList extends Model
{
    //fillやfirstOrCreateをコントローラで使う際は必須
    protected $fillable = [
        'name',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo('App\User');
    }
}
