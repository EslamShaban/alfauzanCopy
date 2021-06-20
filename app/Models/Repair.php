<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Repair extends Model
	{
		protected $table = 'repairs';

		protected $guarded = [];

		public function type()
		{
			return $this->belongsTo( 'App\Models\RepairType', 'type_id' );
		}
	}
