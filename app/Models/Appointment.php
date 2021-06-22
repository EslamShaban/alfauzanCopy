<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    
    protected $table = 'appointments';

    protected $guarded = [];


    public function doctor()
    {

        return $this->belongsTo( 'App\Models\Doctor', 'doctor_id' );
    }


    public function users()
    {
        return $this->belongsToMany( 'App\Models\User', 'orders', 'appoint_id', 'id');
    }

}
