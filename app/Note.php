<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
