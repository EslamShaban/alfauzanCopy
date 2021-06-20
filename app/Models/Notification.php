<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table   = 'notifications';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo( 'APP\User', 'user_id' );
    }
}
