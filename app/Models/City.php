<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $guarded = [];

    public function doctors()
    {

        return $this->hasMany( 'App\Models\Doctor', 'doctor_id', 'id' );
    }

}
