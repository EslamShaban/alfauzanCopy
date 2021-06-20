<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Reason extends Model
	{

//		use SoftDeletes;

		protected $table = 'reasons';

//		protected $dates = ['deleted_at'];

		public function order()
		{
			return $this->hasMany( 'App\Models\Order', 'reason_id' );
		}
	}
