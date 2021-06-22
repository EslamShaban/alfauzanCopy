<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    
    protected $table = 'offers';

    protected $guarded = [];


    public function doctor()
    {

        return $this->belongsTo( 'App\Models\Doctor', 'doctor_id' );
    }

    public function category()
    {

        return $this->belongsTo( 'App\Models\Category', 'category_id' );
    }
}
