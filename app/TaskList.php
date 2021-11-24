<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function cards():HasMany
    {
       return $this->hasMany('App\TaskCard','list_id', 'id');
    }
}
