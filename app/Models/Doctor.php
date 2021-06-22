<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    protected $guarded = [];


    public function appointments()
    {

        return $this->hasMany( 'App\Models\Appointment', 'doctor_id', 'id' );
    }

    public function offers()
    {

        return $this->hasMany( 'App\Models\Offer', 'doctor_id', 'id' );
    }

    public function stars()
    {

        return $this->hasMany( 'App\Models\Star', 'doctor_id', 'id' );
    }

    public function city()
    {

        return $this->belongsTo( 'App\Models\City', 'id');
    }
    
}
