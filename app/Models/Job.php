<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Job extends Model
	{
//		use SoftDeletes;

		protected $table = 'jobs';

//		protected $dates = ['deleted_at'];

		public function user()
		{
			return $this->belongsTo( 'APP\User', 'user_id' );
		}


	}
