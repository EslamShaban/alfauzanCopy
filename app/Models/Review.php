<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Review extends Model
	{

		protected $table = 'review';

		public function user()
		{

			return $this->belongsTo( 'App\User', 'user_id' );
		}

		public function ads()
		{

			return $this->belongsTo( 'App\Models\Ads', 'ads_id' );
		}
	}
